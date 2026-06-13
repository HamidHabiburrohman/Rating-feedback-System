{{-- resources/views/student/profile/partials/form.blade.php --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <!-- Personal Information Card -->
    <div class="lg:col-span-2 bg-white p-10 md:p-12 rounded-3xl shadow-xl">
        <h2 class="flex items-center gap-3 text-2xl font-bold mb-8">
            <span class="material-symbols-outlined text-primary text-3xl">person</span>
            Personal Information
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3 ml-1">Full
                    Name</label>
                <input type="text" name="name" value="{{ old('name', $profile->name) }}"
                    class="w-full bg-surface-container-high border-0 rounded-3xl px-8 py-5 focus:ring-2 focus:ring-primary/30 transition-all text-lg">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3 ml-1">Major
                    / Program</label>
                <input type="text" name="major" value="{{ old('major', $profile->major) }}"
                    class="w-full bg-surface-container-high border-0 rounded-3xl px-8 py-5 focus:ring-2 focus:ring-primary/30 transition-all text-lg">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3 ml-1">Class
                    Year</label>
                <input type="text" name="class_year" value="{{ old('class_year', $profile->class_year) }}"
                    class="w-full bg-surface-container-high border-0 rounded-3xl px-8 py-5 focus:ring-2 focus:ring-primary/30 transition-all text-lg">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3 ml-1">Bio /
                    About Me</label>
                <textarea name="bio" rows="5"
                    class="w-full bg-surface-container-high border-0 rounded-3xl px-8 py-5 focus:ring-2 focus:ring-primary/30 transition-all resize-none">{{ old('bio', $profile->bio) }}</textarea>
            </div>
        </div>
    </div>

    <!-- Contact Card -->
    <div class="lg:col-span-2 bg-white p-10 md:p-12 rounded-3xl shadow-xl">
        <h2 class="flex items-center gap-3 text-2xl font-bold mb-8">
            <span class="material-symbols-outlined text-primary text-3xl">alternate_email</span>
            Contact Details
        </h2>

        <div class="space-y-8">
            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3 ml-1">Email
                    Address</label>
                <input type="email" name="email" value="{{ old('email', $profile->email) }}"
                    class="w-full bg-surface-container-high border-0 rounded-3xl px-8 py-5 focus:ring-2 focus:ring-primary/30 transition-all text-lg">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label
                        class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3 ml-1">Phone
                        Number</label>
                    <input type="tel" name="phone" value="{{ old('phone', $profile->phone) }}"
                        class="w-full bg-surface-container-high border-0 rounded-3xl px-8 py-5 focus:ring-2 focus:ring-primary/30 transition-all text-lg">
                </div>
                <div>
                    <label
                        class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3 ml-1">Location</label>
                    <input type="text" name="location" value="{{ old('location', $profile->location) }}"
                        class="w-full bg-surface-container-high border-0 rounded-3xl px-8 py-5 focus:ring-2 focus:ring-primary/30 transition-all text-lg">
                </div>
            </div>
        </div>
    </div>


    <div class="lg:col-span-2 bg-white p-10 md:p-12 rounded-3xl shadow-xl">

        <!-- Social / Optional -->

        <h2 class="flex items-center gap-3 text-2xl font-bold mb-8">
            <span class="material-symbols-outlined text-primary text-3xl">public</span>
            Digital Presence
        </h2>

        <div class="space-y-8">
            <div>
                <label
                    class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3 ml-1">Portfolio
                    / Website</label>
                <div class="flex items-center bg-surface-container-high rounded-3xl px-6">
                    <span class="material-symbols-outlined text-on-surface-variant mr-4">link</span>
                    <input type="url" name="portfolio_url"
                        value="{{ old('portfolio_url', $profile->portfolio_url ?? '') }}"
                        class="flex-1 bg-transparent border-0 py-5 focus:ring-0 text-lg">
                </div>
            </div>

            <div>
                <label
                    class="block text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-3 ml-1">LinkedIn
                    Profile</label>
                <div class="flex items-center bg-surface-container-high rounded-3xl px-6">
                    <span class="material-symbols-outlined text-on-surface-variant mr-4">share</span>
                    <input type="url" name="linkedin_url"
                        value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}"
                        class="flex-1 bg-transparent border-0 py-5 focus:ring-0 text-lg">
                </div>
            </div>

        </div>
    </div>
</div>