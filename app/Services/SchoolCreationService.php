<?php

namespace App\Services;

use App\Mail\SchoolWelcomeMail;
use App\Models\Tenant;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SchoolCreationService
{
    /**
     * @return array{tenant: Tenant, username: string, password: string, loginUrl: string}
     */
    public function create(string $name, string $level, string $slug, ?string $contactEmail = null): array
    {
        $slug = Str::slug($slug);

        if (Tenant::find($slug)) {
            throw ValidationException::withMessages([
                'subdomain' => 'This school slug is already taken.',
            ]);
        }

        // Creates schema → migrates → seeds (TenantDataSeeder creates school,
        // roles, initial admin user, and stashes credentials in tenant.data).
        $tenant = Tenant::create([
            'id'     => $slug,
            'name'   => $name,
            'status' => 'active',
            'data'   => [
                'level'         => $level,
                'contact_email' => $contactEmail,
            ],
        ]);

        if (config('tenancy.route_mode') === 'subdomain') {
            $domain = config('tenancy.central_domain', 'classmaster.com');
            $tenant->domains()->create(['domain' => "{$slug}.{$domain}"]);
        }

        // VirtualColumn stores extras in the data JSON but surfaces them as direct
        // model attributes. Access them that way, not via $tenant->data[].
        $tenant->refresh();
        $username = $tenant->admin_username  ?? "{$slug}-admin";
        $password = $tenant->admin_temp_pass ?? '(see mailpit)';

        // Clear temp password — don't persist it long-term
        $tenant->admin_temp_pass = null;
        $tenant->save();

        $loginUrl = rtrim(config('app.url'), '/') . '/' . $slug . '/login';
        if (config('tenancy.route_mode') === 'subdomain') {
            $loginUrl = 'https://' . $slug . '.' . config('tenancy.central_domain') . '/login';
        }

        if ($contactEmail) {
            Mail::to($contactEmail)->send(
                new SchoolWelcomeMail($tenant, $loginUrl, $username, $password)
            );
        }

        return compact('tenant', 'username', 'password', 'loginUrl');
    }
}
