<x-layout>
    @push('head')
        {!! RecaptchaV3::initJs() !!}
    @endpush

    <div class="container px-4 py-8">
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 mb-6 rounded">
                {{ session('success') }}
            </div>
        @endif

        <x-card>
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/3 mb-6 md:mb-0 md:mr-6">
                    <x-img src="resources/img/adam-outdoors.jpg" sizes="(min-width: 768px) 33vw, 100vw" alt="Adam smiling on a mountain path" class="rounded-lg shadow-lg w-full" />
                </div>
                <div class="md:w-2/3">
                    <h3>Contact Adam</h3>
                    <p>Get in contact below and I'll get back to you as soon as I can.</p>
                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4 flex flex-col">
                        @csrf

                        <div class="flex-none">
                            <label for="name" class="block text-white mb-1">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:border-blue-500 focus:outline-none">
                            @error('name')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex-none">
                            <label for="email" class="block text-white mb-1">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:border-blue-500 focus:outline-none">
                            @error('email')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex-grow h-full">
                            <label for="message" class="block text-white mb-1">Message</label>
                            <textarea id="message" name="message" required
                                      class="w-full h-full px-4 py-2 rounded bg-gray-700 text-white border border-gray-600 focus:border-blue-500 focus:outline-none">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {!! RecaptchaV3::field('contact') !!}

                        <div class="flex-none">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </x-card>
    </div>
</x-layout>
