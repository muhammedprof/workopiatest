<x-layout>
  <div class="bg-white mx-auto p-8 rounded-lg shadow-md w-full md:max-w-3xl">
    <h2 class="text-4xl text-center font-bold mb-4">Create Job Listing</h2>

    <!-- Form Start -->
    <form method="POST" action="{{ route('jobs.store') }}" enctype="multipart/form-data">
      @csrf

      <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">Job Info</h2>

      <x-inputs.text id="title" name="title" label="Job Title" placeholder="Software Engineer" />
      
     
      <x-inputs.text id="company" name="company" label="Company Name" placeholder="Tech Company Inc." />

      <!-- Job Description -->
      <x-inputs.text-area id="description" name="description" label="Job Description" placeholder="We are seeking a skilled and motivated Software Developer..." />

      <!-- Annual Salary -->
      <x-inputs.text id="salary" name="salary" label="Annual Salary" type="number" placeholder="90000" />

      <!-- Requirements -->
      <x-inputs.text-area id="requirements" name="requirements" label="Requirements" placeholder="Bachelor's degree in Computer Science, 3+ years of experience..." />

      <!-- Benefits -->
      <x-inputs.text-area id="benefits" name="benefits" label="Benefits" placeholder="Health insurance, 401(k), remote work options..." />

      <!-- Tags -->
      <x-inputs.text id="tags" name="tags" label="Tags (comma separated)" placeholder="PHP, Laravel, Remote" />

      <!-- Job Type -->
      <x-inputs.select
        id="job_type"
        name="job_type"
        label="Job Type"
        :options="['full_time' => 'Full-Time', 'part_time' => 'Part-Time', 'contract' => 'Contract']"
        value="{{ old('job_type') }}" />

      <!-- Remote -->
      <x-inputs.select
        id="remote"
        name="remote"
        label="Remote"
        :options="[1 => 'Yes', 0 => 'No']"
        value="{{ old('remote') }}" />

      <h2 class="text-2xl font-bold mb-6 text-center text-gray-500">Company Info</h2>

      <!-- Address -->
      <x-inputs.text id="address" name="address" label="Address" placeholder="123 Main St" />

      <!-- City -->
      <x-inputs.text id="city" name="city" label="City" placeholder="Albany" />

      <!-- State -->
      <x-inputs.text id="state" name="state" label="State" placeholder="NY" />

      <!-- ZIP Code -->
      <x-inputs.text id="zipcode" name="zipcode" label="ZIP Code" placeholder="12203" />

      <!-- Company Name -->
      <x-inputs.text id="company_name" name="company_name" label="Company Name" placeholder="Tech Company Inc." />

      <!-- Company Description -->
      <x-inputs.text-area id="company_description" name="company_description" label="Company Description" placeholder="Tech Company Inc. is a leading provider of innovative software solutions..." />

      <!-- Company Website -->
      <x-inputs.text id="company_website" name="company_website" label="Company Website" type="url" placeholder="https://www.techcompany.com" />

      <!-- Contact Phone -->
      <x-inputs.text id="contact_phone" name="contact_phone" label="Contact Phone" placeholder="(555) 123-4567" />

      <!-- Contact Email -->
      <x-inputs.text id="contact_email" name="contact_email" label="Contact Email" type="email" placeholder="contact@techcompany.com" />  

      <!-- Company Logo -->
      <x-inputs.file id="company_logo" name="company_logo" label="Company Logo" accept="image/*" />

      <!-- Submit Button -->
      <button type="submit"
        class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3 rounded focus:outline-none">
        Save
      </button>
    </form>
    <!-- Form End -->

  </div>
</x-layout>