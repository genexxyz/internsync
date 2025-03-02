<x-form-layout>
    <form method="POST" action="{{ route('user.form.admin.submit') }}">
        @csrf
    
        <div>
            <label for="field_name">Field Name</label>
            <input type="text" id="field_name" name="field_name" required>
        </div>
    
        <button type="submit">Submit</button>
    </form>
    
</x-form-layout>
