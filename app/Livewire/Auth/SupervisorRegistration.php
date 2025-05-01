<?php

namespace App\Livewire\Auth;

use App\Models\AcceptanceLetter;
use App\Models\User;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Department;

class SupervisorRegistration extends Component
{
    use WithFileUploads;

    public $reference;
    public $acceptanceLetter;
    public $email;
    public $password;
    public $password_confirmation;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $suffix;
    public $contact;
    public $position;
    public $document;
    public $acceptTerms = false;

    protected $rules = [
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'first_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s.]+$/'],
        'middle_name' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z\s.]+$/'],
        'last_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s.]+$/'],
        'suffix' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z\s.]+$/'],
        'contact' => 'required|digits:11',
        'position' => 'required|string|max:255',
        'document' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        'acceptTerms' => 'accepted'
    ];

    public function mount($reference)
    {
        $this->reference = $reference;
        $this->acceptanceLetter = AcceptanceLetter::where('reference_link', $reference)
            ->where('is_generated', true)
            ->firstOr(function () {
                abort(404, 'Invalid or expired registration link.');
            });

        // Pre-fill email from acceptance letter
        $this->email = $this->acceptanceLetter->email;
    }

    public function updatedEmail()
    {
        if ($this->email !== $this->acceptanceLetter->email) {
            $this->addError('email', 'Email must match the one provided in the acceptance letter.');
        }
    }

    private function processDocument($user)
    {
        if (!$this->document) {
            return null;
        }

        try {
            $extension = $this->document->getClientOriginalExtension();
            $fileName = sprintf(
                '%s_supervisor_%s_%s_%s.%s',
                $user->id,
                Str::slug($this->last_name),
                Str::slug($this->first_name),
                Str::random(8),
                $extension
            );

            $path = $this->document->storeAs('documents', $fileName, 'public');
            return $path;
        } catch (\Exception $e) {
            logger()->error('Document upload failed:', ['error' => $e->getMessage()]);
            throw new \Exception('Failed to upload document. Please try again.');
        }
    }

    public function register()
{
    if ($this->email !== $this->acceptanceLetter->email) {
        $this->addError('email', 'Email must match the one provided in the acceptance letter.');
        return;
    }

    $this->validate();

    try {
        // Find or get company and department
        $company = Company::firstOrCreate(
            ['company_name' => $this->acceptanceLetter->company_name],
            ['address' => $this->acceptanceLetter->address]
        );

        $department = Department::firstOrCreate(
            [
                'company_id' => $company->id,
                'department_name' => $this->acceptanceLetter->department_name
            ]
        );

        // Create user
        $user = User::create([
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'supervisor',
            'email_verified_at' => now(),
            'is_verified' => true,
            'form_completed' => true
        ]);

        // Process document
        $documentPath = $this->processDocument($user);

        // Create supervisor profile
        $supervisor = Supervisor::create([
            'user_id' => $user->id,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            'contact' => $this->contact,
            'position' => $this->position,
            'company_id' => $company->id,
            'supporting_doc' => $documentPath,
            'company_department_id' => $department->id
        ]);

        // Mark acceptance letter as verified
        $this->acceptanceLetter->update(['is_verified' => true]);

        // Login user
        Auth::login($user);
        
        return redirect()->route('supervisor.dashboard')->dispatch('alert', type: 'success', text: 'Registration successful!');
        

    } catch (\Exception $e) {
        logger()->error('Supervisor registration failed:', [
            'error' => $e->getMessage(),
            'company_name' => $this->acceptanceLetter->company_name,
            'department_name' => $this->acceptanceLetter->department_name
        ]);
        $this->addError('registration', 'Registration failed. Please try again.');
    }
}

    public function render()
    {
        return view('livewire.auth.supervisor-registration')
            ->layout('layouts.guest');
    }
}