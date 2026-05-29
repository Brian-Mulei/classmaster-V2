<x-mail::message>
# Welcome to ClassMaster — {{ $tenant->name }} is live!

Your school has been successfully provisioned. Here are the **first login credentials** for the school administrator.

<x-mail::panel>
**Login URL:** {{ $loginUrl }}
**Username:** `{{ $adminUsername }}`
**Temporary password:** `{{ $adminPassword }}`
</x-mail::panel>

> Log in and change this password immediately under **Settings → My Account**.

---

## What to set up first

1. **Grading scales** — define your letter/band/points system
2. **Curriculum** — add syllabuses, subjects, and topics
3. **Grade structure** — create grade levels and streams
4. **Academic calendar** — set up the current year and terms
5. **Add staff** — create accounts for teachers and other staff
6. **Enrol students** — add students and assign them to classes

<x-mail::button :url="$loginUrl">
Sign in to {{ $tenant->name }}
</x-mail::button>

---

School level: {{ ucfirst($tenant->data['level'] ?? 'mixed') }}
Slug / ID: `{{ $tenant->id }}`

Thanks,
**The ClassMaster Team**
</x-mail::message>
