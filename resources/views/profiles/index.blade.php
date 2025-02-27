<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Choose Profile</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    netflix: {
                        red: '#E50914',
                        black: '#141414',
                        dark: '#181818',
                        gray: '#808080',
                    }
                }
            }
        }
    }
</script>
<style type="text/tailwindcss">
    @layer utilities {
        .profile-border {
            @apply border-4 rounded-md transition-all duration-300;
        }
        .profile-card {
            @apply flex flex-col items-center justify-center cursor-pointer transition-transform duration-300 hover:scale-110;
        }
        .pin-input {
            @apply w-16 h-16 text-center text-2xl bg-netflix-dark border-2 border-gray-700 rounded-md focus:outline-none focus:border-white;
        }
    }
</style>
</head>
<body class="bg-netflix-black text-white min-h-screen flex flex-col">
<div class="container mx-auto px-4 py-16 flex-grow flex flex-col">
    <h1 class="text-4xl font-bold text-center mb-16">Pick a Profile</h1>
    
    <div class="flex flex-wrap justify-center gap-8 max-w-4xl mx-auto">
        <!-- Dynamically load profiles from the database -->
        @foreach($profiles as $profile)
            <div class="profile-card" data-profile-id="{{ $profile->id }}">
                <div class="profile-border border-{{ $profile->color }} mb-4">
                    <div class="w-32 h-32 bg-{{ $profile->color }} flex items-center justify-center">
                        <span class="text-4xl">{{ substr($profile->name, 0, 1) }}</span>
                    </div>
                </div>
                <span class="text-gray-300">{{ $profile->name }}</span>
            </div>
        @endforeach
        
        <!-- Add Profile Button -->
        <div id="add-profile-btn" class="profile-card">
            <div class="profile-border border-gray-700 mb-4">
                <div class="w-32 h-32 bg-netflix-dark flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
            </div>
            <span class="text-gray-300">Add Profile</span>
        </div>
    </div>
</div>

<!-- New Profile Modal -->
<div id="new-profile-modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
    <div class="bg-netflix-dark p-8 rounded-md max-w-md w-full">
        <h2 class="text-2xl font-bold mb-6">Create Profile</h2>
        
        <form id="new-profile-form" method="POST" action="{{route('profiles.store')}} " class="space-y-6">
        @csrf

            <div>
                <label for="profile-name" class="block text-sm font-medium text-gray-300 mb-2">Name</label>
                <input type="text" id="profile-name" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-netflix-red" required>
            </div>
            
            <div>
                <label for="profile-pin" class="block text-sm font-medium text-gray-300 mb-2">PIN (4 digits)</label>
                <input type="password" placeholder="1596" id="profile-pin" class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-netflix-red" maxlength="4" pattern="[0-9]{4}" inputmode="numeric" required>
                <p class="text-xs text-gray-400 mt-1">Create a 4-digit PIN for profile access</p>
            </div>
            
            <div>
                <label for="profile-color" class="block text-sm font-medium text-gray-300 mb-2">Profile Color</label>
                <div class="flex flex-wrap gap-3">
                    @foreach(['red-500', 'blue-500', 'green-500', 'yellow-500', 'purple-500', 'pink-500'] as $color)
                        <div class="color-option cursor-pointer w-10 h-10 rounded-full bg-{{ $color }} border-2 border-transparent hover:border-white" data-color="{{ $color }}"></div>
                    @endforeach
                </div>
                <input type="hidden" id="selected-color" value="red-500">
            </div>
            
            <div class="flex justify-end space-x-4 pt-4">
                <button type="button" id="cancel-profile" class="px-6 py-2 bg-transparent border border-gray-600 rounded-md hover:bg-gray-800">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-netflix-red rounded-md hover:bg-red-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- PIN Verification Modal -->
<div id="pin-modal" class="fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50 hidden">
    <div class="bg-netflix-dark p-8 rounded-md max-w-md w-full">
        <h2 class="text-2xl font-bold mb-6">Enter PIN</h2>
        <p id="pin-profile-name" class="text-lg text-gray-300 mb-6">Verify access to <span>Profile</span></p>
        
        <div class="flex justify-center space-x-4 mb-6">
            <input type="password" class="pin-input" maxlength="1" inputmode="numeric" data-index="0">
            <input type="password" class="pin-input" maxlength="1" inputmode="numeric" data-index="1">
            <input type="password" class="pin-input" maxlength="1" inputmode="numeric" data-index="2">
            <input type="password" class="pin-input" maxlength="1" inputmode="numeric" data-index="3">
        </div>
        
        <p id="pin-error" class="text-netflix-red text-center hidden mb-4">Incorrect PIN. Please try again.</p>
        
        <div class="flex justify-end">
            <button type="button" id="cancel-pin" class="px-6 py-2 bg-transparent border border-gray-600 rounded-md hover:bg-gray-800">Cancel</button>
        </div>
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        // Use the profiles passed from the controller via Blade (converted to JSON)
        const profiles = @json($profiles);

        // Elements for New Profile Modal
        const addProfileBtn = document.getElementById('add-profile-btn');
        const newProfileModal = document.getElementById('new-profile-modal');
        const newProfileForm = document.getElementById('new-profile-form');
        const cancelProfileBtn = document.getElementById('cancel-profile');
        const colorOptions = document.querySelectorAll('.color-option');
        const selectedColorInput = document.getElementById('selected-color');

        // Elements for PIN Modal
        const pinModal = document.getElementById('pin-modal');
        const pinInputs = document.querySelectorAll('.pin-input');
        const pinProfileNameSpan = document.getElementById('pin-profile-name').querySelector('span');
        const pinError = document.getElementById('pin-error');
        const cancelPinBtn = document.getElementById('cancel-pin');

        let currentProfileId = null;

        // Show Add Profile Modal
        addProfileBtn.addEventListener('click', function() {
            newProfileModal.classList.remove('hidden');
        });

        // Hide Add Profile Modal
        cancelProfileBtn.addEventListener('click', function() {
            newProfileModal.classList.add('hidden');
            newProfileForm.reset();
            resetColorSelection();
        });

        // Color Selection
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove selection from all options
                colorOptions.forEach(opt => opt.classList.remove('border-white'));
                // Add selection to clicked option
                this.classList.add('border-white');
                // Update hidden input
                selectedColorInput.value = this.dataset.color;
            });
        });

        // Reset color selection to default
        function resetColorSelection() {
            colorOptions.forEach(opt => opt.classList.remove('border-white'));
            colorOptions[0].classList.add('border-white');
            selectedColorInput.value = 'red-500';
        }

        // New Profile Form Submission via AJAX
        newProfileForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const name = document.getElementById('profile-name').value;
            const pin = document.getElementById('profile-pin').value;
            const color = selectedColorInput.value;

            // Validate PIN on client-side
            if (!/^\d{4}$/.test(pin)) {
                alert('PIN must be exactly 4 digits');
                return;
            }

            // Prepare data to send
            const data = { name, pin, color };

            fetch("{{ route('profiles.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page or update the DOM with the new profile
                    window.location.reload();
                } else {
                    alert('Error creating profile: ' + (data.message || 'Unknown error.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        // Show PIN Modal when a profile is clicked
        document.querySelectorAll('.profile-card').forEach(card => {
            if (card.id !== 'add-profile-btn') {
                card.addEventListener('click', function() {
                    currentProfileId = parseInt(this.dataset.profileId);
                    const profile = profiles.find(p => p.id === currentProfileId);

                    if (profile) {
                        pinProfileNameSpan.textContent = profile.name;
                        pinModal.classList.remove('hidden');
                        pinError.classList.add('hidden');
                        clearPinInputs();
                        pinInputs[0].focus();
                    }
                });
            }
        });

        // Hide PIN Modal
        cancelPinBtn.addEventListener('click', function() {
            pinModal.classList.add('hidden');
            clearPinInputs();
        });

        // PIN Input Handling
        pinInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                if (this.value.length === 1) {
                    // Move to next input
                    if (index < pinInputs.length - 1) {
                        pinInputs[index + 1].focus();
                    } else {
                        // All inputs filled; validate PIN
                        validatePin();
                    }
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    pinInputs[index - 1].focus();
                }
            });
        });

        // Clear PIN inputs
        function clearPinInputs() {
            pinInputs.forEach(input => input.value = '');
        }

        // Validate PIN
        function validatePin() {
        const enteredPin = Array.from(pinInputs).map(input => input.value).join('');

        // Prepare data for the AJAX call
        const data = {
            profile_id: currentProfileId,
            pin: enteredPin,
        };

        fetch("{{ route('profiles.verifyPin') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                pinModal.classList.add('hidden');
                window.location.href = '/dashboard';
            } else {
                // Display error message if the PIN is incorrect
                pinError.classList.remove('hidden');
                clearPinInputs();
                pinInputs[0].focus();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
        }

    });
</script>
</body>
</html>
