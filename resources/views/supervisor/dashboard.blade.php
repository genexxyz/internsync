<x-app-layout>
    <x-breadcrumbs :breadcrumbs="[]" />
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-6 overflow-hidden">

        <div class="rounded-lg shadow-md bg-secondary flex justify-between items-center px-4 py-3">
            <div class="text-center text-white">
                <p class="text-5xl font-extrabold">0</p>
                <p class="text-lg font-medium">HANDLED INTERNS</p>
            </div>
            <div>
                <i class="fa fa-clock text-white text-6xl"></i>
            </div>
        </div>


        <div class="flex flex-col bg-white rounded-lg shadow-md">
            <!-- Top Section -->
            <div class="bg-secondary text-white rounded-t-lg flex items-center justify-between px-4 py-3">
                <div class="flex-grow text-center ml-14">
                    <p class="text-4xl font-extrabold">0</p>
                    <p class="text-lg font-medium">ON-GOING</p>
                </div>
                <div>
                    <i class="fa fa-users-between-lines text-5xl"></i>
                </div>
            </div>
            <!-- Bottom Section -->
            <div class="py-3 text-center text-sm underline text-gray-600 hover:text-gray-800">
                <a href="#" class="flex items-center justify-center gap-1">
                    VIEW INTERNS <i class="fa fa-circle-info"></i>
                </a>
            </div>
        </div>

        <div class="flex flex-col bg-white rounded-lg shadow-md">
            <!-- Top Section -->
            <div class="bg-secondary text-white rounded-t-lg flex items-center justify-between px-4 py-3">
                <div class="flex-grow text-center ml-14">
                    <p class="text-4xl font-extrabold">0</p>
                    <p class="text-lg font-medium">FINISHED</p>
                </div>
                <div>
                    <i class="fa fa-user-check text-5xl"></i>
                </div>
            </div>
            <!-- Bottom Section -->
            <div class="py-3 text-center text-sm underline text-gray-600 hover:text-gray-800">
                <a href="#" class="flex items-center justify-center gap-1">
                    VIEW INSTERNS <i class="fa fa-circle-info"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 p-6">
        <div class="flex flex-col bg-white rounded-lg shadow-md">
            <!-- Top Section -->
            <div class="bg-secondary text-white rounded-t-lg flex items-center justify-between px-4 py-3">
                <div class="flex-grow text-center">
                    <p class="text-xl font-extrabold">Notifications</p>
                </div>
            </div>
            <!-- Bottom Section -->
            <div class="py-3 text-center text-sm text-gray-600">
                <div class="overflow-x-auto">
                    <table class="table-auto w-full text-sm">
                        <tbody>
                            <tr class="flex flex-row">
                                <div class="flex flex-row items-center justify-center gap-5 px-5 py-3 border-b-2">
                                    <p>Juan Dela Cruz submitted his report from 09/16/2024 to 9/20/2024</p>
                                    <a href="#"
                                        class="bg-primary text-sm py-1 px-4 rounded-full text-white shadow-md hover:shadow-none hover:bg-accent">VIEW</a>
                                </div>
                            </tr>
                            <tr class="flex flex-row">
                                <div class="flex flex-row items-center justify-center gap-5 px-5 py-3 border-b-2">
                                    <p>Danilo Cruz submitted his report from 09/16/2024 to 9/20/2024</p>
                                    <a href="#"
                                        class="bg-primary text-sm py-1 px-4 rounded-full text-white shadow-md hover:shadow-none hover:bg-accent">VIEW</a>
                                </div>
                            </tr>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
