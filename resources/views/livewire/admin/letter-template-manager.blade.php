<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Letter Template Manager</h2>
        <button wire:click="createTemplate" class="px-4 py-2 bg-primary text-white rounded-md">
            New Template
        </button>
    </div>

    @if($isEditing || $templateContent !== null)
        <div class="mb-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Template Title</label>
                <input type="text" wire:model="templateTitle" class="mt-1 block w-full rounded-md border-gray-300">
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700">Content</label>
                    <textarea wire:model="templateContent" rows="15" 
                        class="mt-1 block w-full rounded-md border-gray-300"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Available Variables</label>
                    <div class="mt-1 bg-gray-50 p-4 rounded-md">
                        @foreach($availableVariables as $var => $desc)
                            <div class="mb-2">
                                <code class="text-sm bg-gray-200 px-1 rounded">{{ $var }}</code>
                                <p class="text-xs text-gray-600">{{ $desc }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button wire:click="$set('templateContent', null)" 
                    class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button wire:click="saveTemplate"
                    class="px-4 py-2 text-white bg-primary rounded-md hover:bg-primary-dark">
                    Save Template
                </button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Title
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Last Modified
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($templates as $template)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $template->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $template->updated_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($template->is_active)
                                <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                    Active
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="editTemplate({{ $template->id }})" 
                                class="text-primary hover:text-primary-dark">
                                Edit
                            </button>
                            @if(!$template->is_active)
                                <button wire:click="setActive({{ $template->id }})" 
                                    class="ml-3 text-green-600 hover:text-green-900">
                                    Set Active
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No templates found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>