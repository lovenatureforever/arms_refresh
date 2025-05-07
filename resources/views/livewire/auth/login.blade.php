<div class="w-full md:w-1/2 lg:w-1/3 2xl:w-1/4">
    <div class="card overflow-hidden rounded-none sm:rounded-md">
        <div class="p-6">
            <a class="mb-8 block" href="#">
                <h1 class="text-center text-2xl font-bold">ARMS</h1>
                <div class="text-md text-center">Audit Report Management System</div>
            </a>

            @if (session()->has('error'))
                <div class="mb-4 rounded-md bg-danger/25 p-4 text-sm text-danger" role="alert">
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif

            <form wire:submit="login">

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-600 dark:text-gray-200" for="LoggingEmailAddress">Email Address</label>
                    <input class="form-input" id="LoggingEmailAddress" name="email" type="email" wire:model="email" placeholder="Enter your email">
                </div>

                <div class="mb-4">
                    <label class="mb-2 block text-sm font-medium text-gray-600 dark:text-gray-200" for="loggingPassword">Password</label>
                    <input class="form-input" id="loggingPassword" name="password" type="password" wire:model="password" placeholder="Enter your password">
                </div>

                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <input class="form-checkbox rounded" id="checkbox-signin" type="checkbox" wire:model="remember">
                        <label class="ms-2" for="checkbox-signin">Remember me</label>
                    </div>
                    {{-- <a class="border-b border-dashed border-primary text-sm text-primary" href="{{ route('second', ['auth', 'recoverpw']) }}">Forget Password ?</a> --}}
                </div>

                <div class="mb-6 flex justify-center">
                    <button class="btn w-full bg-primary text-white"> Log In</button>
                </div>
            </form>
        </div>
    </div>
</div>
