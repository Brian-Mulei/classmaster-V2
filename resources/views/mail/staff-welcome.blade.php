<x-mail::message>
# Welcome to {{ $schoolName }}, {{ $staffName }}!

An account has been created for you on ClassMaster.

**School:** {{ $schoolName }}
**Username:** `{{ $username }}`
**Temporary password:** `{{ $temporaryPassword }}`

<x-mail::button :url="$loginUrl">
Sign in now
</x-mail::button>

<x-mail::panel>
**Important:** Please change your password after your first login. Go to **Settings → My Account**.
</x-mail::panel>

If you have any issues signing in, contact your school administrator.

Thanks,
**The ClassMaster Team**
</x-mail::message>
