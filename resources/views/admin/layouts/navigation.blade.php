<aside class="w-64 bg-gray-800 text-white flex-shrink-0">
    <div class="p-6">
        <h2 class="text-xl font-bold">Admin Panel</h2>
        <p class="text-gray-400 text-sm mt-1">Examination System</p>
    </div>
    
    <nav class="mt-6">
        <a href="{{ route('admin.dashboard') }}" 
           class="block px-6 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
            </svg>
            Dashboard
        </a>
        
        <a href="{{ route('admin.semesters.index') }}" 
           class="block px-6 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.semesters.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>
            Semesters
        </a>
        
        <a href="{{ route('admin.subjects.index') }}" 
           class="block px-6 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.subjects.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
            </svg>
            Subjects
        </a>
        
        <a href="{{ route('admin.students.index') }}" 
           class="block px-6 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.students.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
            </svg>
            Students
        </a>
        
        <a href="{{ route('admin.results.index') }}" 
           class="block px-6 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.results.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            Results
        </a>
        
        <a href="{{ route('admin.grade-scales.index') }}" 
           class="block px-6 py-3 hover:bg-gray-700 transition {{ request()->routeIs('admin.grade-scales.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}">
            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
            </svg>
            Grade Scales
        </a>
        
        <hr class="my-4 border-gray-700">
        
        <a href="{{ route('dashboard') }}" class="block px-6 py-3 hover:bg-gray-700 transition">
            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
            </svg>
            Student View
        </a>
    </nav>
</aside>
