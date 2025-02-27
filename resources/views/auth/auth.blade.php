<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="h-screen text-base-content overflow-hidden">
<div id="auth-wrapper" class="flex h-full w-full bg-gray-50">
    <div class="hidden lg:flex lg:w-1/2 bg-black p-12 items-center justify-center">
        <div class="max-w-xl text-center">
        <img class="w-full h-auto mb-8" src="{{ asset('images/6399a4d27711a5ad2c9bf5cd_ben-sweet-2LowviVHZ-E-unsplash-1.jpeg') }}" >
        <p class="text-indigo-100 text-lg">Connect, Read, and Participate in worlds that matter to you.</p>
        </div>
    </div>
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6">
        <div class="w-full max-w-md space-y-8">
        <div class="flex space-x-4 border-b border-gray-200">
            <button onclick="toggleForm('signin')" id="signin-tab" class="cursor-pointer px-4 py-2 text-sm font-medium text-black border-b-2 border-black">Sign In</button>
            <button onclick="toggleForm('signup')" id="signup-tab" class="cursor-pointer px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Sign Up</button>
        </div>
        <!-- Sign In Form -->
        <form id="signin-form" action='/auth/login' method='post' class="space-y-6">
            @csrf
            <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address </label>
            <input type="email" name="email" value="{{old('email')}}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" placeholder="name@company.com">
            @error('email')
                <p class="text-red-500">{{$message}}</p>
            @enderror
            </div>
            <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" placeholder="••••••••">
            @error('password')
                <p class="text-red-500">{{$message}}</p>
            @enderror
            </div>
            <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                <label class="ml-2 text-sm text-gray-600">Remember me</label>
            </div>
            <span class="text-sm text-green-600 hover:text-green-500 cursor-pointer">Forgot password?</span>
            </div>
            <button class="w-full cursor-pointer bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-800 focus:ring-4 focus:ring-indigo-200">
            Sign in
            </button>
            <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-gray-50 text-gray-500">Or continue with</span>
            </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
            <a href="/auth/google" class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fa-brands fa-google text-xl text-gray-600 mr-2"></i>
                Google
            </a>
            <button class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fa-brands fa-facebook text-xl text-blue-600 mr-2"></i>
                Facebook
            </button>
            </div>
            <div class="text-center text-sm text-gray-600">
            Don't have an account? 
            <span onclick="toggleForm('signup')" class="text-green-600 hover:text-green-500 cursor-pointer">Sign up</span>
            </div>
        </form>
    
        <!-- Sign Up Form -->
        <form id="signup-form" action='/auth/register' method='post' class="hidden space-y-6">
            @csrf
            <div class="grid grid-cols-2 gap-4">
            
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" placeholder="Doe">
            </div>
            @error('name')
                <p class="text-red-500">{{$message}}</p>
            @enderror
            </div>
            <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
            <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" placeholder="name@company.com">
            </div>
            @error('email')
                <p class="text-red-500">{{$message}}</p>
            @enderror
            <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" placeholder="••••••••">
            </div>
            @error('password')
                <p class="text-red-500">{{$message}}</p>
            @enderror
            <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" placeholder="••••••••">
            </div>
            @error('password_confirmation')
                <p class="text-red-500">{{$message}}</p>
            @enderror
            <div>
                <label for="pin" class="block text-sm font-medium text-gray-700 mb-1">Choose Pin of the first profile</label>
                <input type="text" name="pin" inputmode="numeric" maxlength="4" pattern="\d{4}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600" placeholder="1589">
            </div>
            @error('pin')
                <p class="text-red-500">{{$message}}</p>
            @enderror
            <div class="flex items-center">
            <input type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
            <label class="ml-2 text-sm text-gray-600">I agree to the <span class="text-green-600 cursor-pointer">Terms of Service</span> and <span class="text-green-600 cursor-pointer">Privacy Policy</span></label>
            </div>
            <button class="w-full cursor-pointer bg-black text-white py-2 px-4 rounded-lg hover:bg-gray-800 focus:ring-4 focus:ring-green-200">
            Create Account
            </button>
            
            <div class="text-center text-sm text-gray-600">
            Already have an account? 
            <span onclick="toggleForm('signin')" class="text-green-600 hover:text-green-500 cursor-pointer">Sign in</span>
            </div>
        </form>
        </div>
    </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Handle form validation
    const signinForm = document.getElementById('signin-form');
    const signupForm = document.getElementById('signup-form');
    signinForm.reset();
    signupForm.reset();
    });

    function toggleForm(formType) {
    const signinForm = document.getElementById('signin-form');
    const signupForm = document.getElementById('signup-form');
    const signinTab = document.getElementById('signin-tab');
    const signupTab = document.getElementById('signup-tab');

    if (formType === 'signup') {
        signinForm.classList.add('hidden');
        signupForm.classList.remove('hidden');
        signinTab.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600');
        signinTab.classList.add('text-gray-500');
        signupTab.classList.remove('text-gray-500');
        signupTab.classList.add('text-black', 'border-b-2', 'border-black');
    } else {
        signupForm.classList.add('hidden');
        signinForm.classList.remove('hidden');
        signupTab.classList.remove('text-indigo-600', 'border-b-2', 'border-indigo-600');
        signupTab.classList.add('text-black');
        signinTab.classList.remove('text-gray-500');
        signinTab.classList.add('text-black', 'border-b-2', 'border-black');
    }
    }
</script>
</body>
</html>