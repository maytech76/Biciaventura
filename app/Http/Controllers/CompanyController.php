<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){

        {
            Log::debug('CompanyController@index - Accediendo a gestión de compañías');
            
            // Obtener la primera compañía registrada
            $company = Company::first();
            
            Log::info('CompanyController@index - Compañía obtenida', [
                'exists' => $company ? true : false,
                'company_id' => $company->id ?? null,
                'company_name' => $company->name ?? 'Sin compañías registradas'
            ]);
            
            return view('admin.companies.index', compact('company'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){

        Log::debug('CompanyController@create - Accediendo a formulario de creación');
        
        // Verificar si ya existe una empresa registrada
        $existingCompany = Company::first();
        $companyExists = $existingCompany ? true : false;
        
        Log::info('CompanyController@create - Verificación de empresa existente', [
            'company_exists' => $companyExists,
            'company_id' => $existingCompany->id ?? null,
            'company_name' => $existingCompany->name ?? null
        ]);
        
        return view('admin.companies.create', compact('companyExists', 'existingCompany'));
   
    }

    public function store(Request $request){

        // 1. VALIDACIÓN DE DATOS
        $validatedData = $request->validate([
            'doc'       => 'required|string|max:15|unique:companies,doc',
            'name'      => 'required|string|max:150|unique:companies,name',
            'email'     => 'required|email|max:150|unique:companies,email',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:200',
            'website'   => 'nullable|string|max:150',
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'city'      => 'nullable|string|max:150',
            'country'   => 'nullable|string|max:150',
            'status'    => 'required|in:active,inactive'
        ]);

        Log::debug('CompanyController@store - Validación pasada', [
            'validated_data' => $validatedData
        ]);

        try {
            // 2. GENERAR CÓDIGO DE LICENCIA ÚNICO
            $licenseCode = $this->generateLicenseCode($validatedData);
            
            Log::debug('CompanyController@store - Código de licencia generado', [
                'license_code' => $licenseCode
            ]);

            // 3. PREPARAR ARRAY DE DATOS (INCLUYENDO LICENCIA)
            $companyData = [
                'doc'       => $validatedData['doc'],
                'name'      => $validatedData['name'],
                'email'     => $validatedData['email'],
                'phone'     => $validatedData['phone'] ?? null,
                'address'   => $validatedData['address'] ?? null,
                'website'   => $validatedData['website'] ?? null,
                'city'      => $validatedData['city'] ?? null,
                'country'   => $validatedData['country'] ?? null,
                'status'    => $validatedData['status'],
                'license'   => $licenseCode, // ← NUEVO CAMPO
            ];

            // 4. PROCESAR LOGO SI EXISTE
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('companies', 'public');
                $companyData['logo'] = $logoPath;
                
                Log::debug('CompanyController@store - Logo guardado', ['path' => $logoPath]);
            }

            // 5. CREAR REGISTRO EN BASE DE DATOS
            Log::debug('CompanyController@store - Creando compañía en BD');
            $company = Company::create($companyData);
            
            Log::info('CompanyController@store - Compañía creada exitosamente', [
                'company_id' => $company->id,
                'company_name' => $company->name,
                'license_code' => $company->license
            ]);

            // 6. REDIRECCIONAR CON MENSAJE DE ÉXITO
            return redirect()
                ->route('companies.index')
                ->with('success', '¡Compañía creada exitosamente! Código de licencia: ' . $licenseCode);

        } catch (\Exception $e) {
            // 7. MANEJO DE ERRORES
            Log::error('CompanyController@store - Error al crear compañía', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (isset($logoPath)) {
                Storage::disk('public')->delete($logoPath);
                Log::info('Logo eliminado por error', ['path' => $logoPath]);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear la compañía: ' . $e->getMessage());
        }
    }

    /**
     * Genera un código de licencia único basado en datos de la empresa
     * 
     * @param array $data Datos de la empresa (name, doc, phone)
     * @return string Código de licencia alfanumérico
     */
    private function generateLicenseCode(array $data): string{
        
        // 1. Obtener partes del nombre (primeras 3 letras, sin espacios)
        $nameParts = explode(' ', trim($data['name']));
        $nameCode = '';
        
        foreach ($nameParts as $part) {
            if (!empty($part)) {
                $nameCode .= strtoupper(substr($part, 0, 1));
            }
        }
        
        // Asegurar mínimo 3 caracteres
        $nameCode = str_pad(substr($nameCode, 0, 3), 3, 'X');
        
        // 2. Obtener últimos 4 dígitos del DOC (o completar con ceros)
        $docDigits = preg_replace('/[^0-9]/', '', $data['doc']);
        $docCode = str_pad(substr($docDigits, -4), 4, '0', STR_PAD_LEFT);
        
        // 3. Obtener últimos 2 dígitos del teléfono (o usar 00)
        $phoneDigits = preg_replace('/[^0-9]/', '', $data['phone'] ?? '');
        $phoneCode = str_pad(substr($phoneDigits, -2), 2, '0', STR_PAD_LEFT);
        
        // 4. Fecha actual (formato: Ymd = AñoMesDía)
        $dateCode = date('Ymd');
        
        // 5. Generar hash único basado en todos los datos
        $uniqueString = $data['name'] . $data['doc'] . now()->timestamp . uniqid();
        $hashCode = strtoupper(substr(md5($uniqueString), 0, 6));
        
        // 6. COMBINAR TODOS LOS COMPONENTES
        // Formato: XXX-0000-00-YYYYMMDD-HHHHHH
        $licenseCode = sprintf(
            '%s-%s-%s-%s-%s',
            $nameCode,
            $docCode,
            $phoneCode,
            $dateCode,
            $hashCode
        );
        
        return $licenseCode;
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
