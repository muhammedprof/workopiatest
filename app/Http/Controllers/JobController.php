<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Job;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class JobController extends Controller
{
    use AuthorizesRequests;
    public function index(): View
    {
        $title = 'Available Jobs';
        $jobs = Job::paginate(9);
        return view('jobs.index', compact('jobs', 'title'));
    }

    public function create(): View
    {
        return view('jobs.create');
    }


    public function store(Request $request): RedirectResponse
{
    
    // Define allowed job types (keys match form values, values match DB enum)
    $typeMap = [
        'full_time'   => 'Full-Time',
        'part_time'   => 'Part-Time',
        'contract'    => 'Contract',
        'temporary'   => 'Temporary',
        'internship'  => 'Internship',
        'volunteer'   => 'Volunteer',
        'on_call'     => 'On-Call',
    ];

    // Validate the incoming request data
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'salary' => 'required|integer',
        'tags' => 'nullable|string',
        'job_type' => ['required', 'string', \Illuminate\Validation\Rule::in(array_keys($typeMap))],
        'remote' => 'required|boolean',
        'requirements' => 'nullable|string',
        'benefits' => 'nullable|string',
        'address' => 'nullable|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'zipcode' => 'required|string',
        'contact_email' => 'required|email',
        'contact_phone' => 'nullable|string',
        'company_name' => 'required|string', //Company name is required
        'company_description' => 'nullable|string',
        'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'company_website' => 'nullable|url',
    ]);

    // map submitted job_type to the database enum value
    if (isset($validatedData['job_type'])) {
        $validatedData['job_type'] = $typeMap[$validatedData['job_type']];
    }

     // Check if a file was uploaded
    if ($request->hasFile('company_logo')) {
    // Store the file and get the path
    $path = $request->file('company_logo')->store('logos', 'public');

    // Add the path to the validated data array
    $validatedData['company_logo'] = $path;
    }
    $user = Auth::user();
    $validatedData['user_id'] = $user->id;

    // Create a new job listing with the validated data
    Job::create($validatedData);

    return redirect()->route('jobs.index')->with('success', 'Job listing created successfully!');
    }

    

    public function show(Job $job): View
    {
        return view('jobs.show')->with('job', $job);
    }
    

    public function edit(Job $job): View
    {
            // Check if the user is authorized
            $this->authorize('update', $job);
            return view('jobs.edit')->with('job', $job);
    }

    public function update(Request $request,  Job $job): RedirectResponse
{
    // Check if the user is authorized
    $this->authorize('update', $job);
    // Validate the incoming request data
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'salary' => 'required|integer',
        'tags' => 'nullable|string',
        'job_type' => 'required|string',
        'remote' => 'required|boolean',
        'requirements' => 'nullable|string',
        'benefits' => 'nullable|string',
        'address' => 'nullable|string',
        'city' => 'required|string',
        'state' => 'required|string',
        'zipcode' => 'required|string',
        'contact_email' => 'required|email',
        'contact_phone' => 'nullable|string',
        'company_name' => 'required|string|max:255',
        'company_description' => 'nullable|string',
        'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'company_website' => 'nullable|url',
    ]);


    // Check if a file was uploaded
    if ($request->hasFile('company_logo')) {
        // Delete the old company logo from storage
        if ($job->company_logo) {
            Storage::delete('public/logos/' . basename($job->company_logo));
        }
        // Store the file and get the path
        $path = $request->file('company_logo')->store('logos', 'public');

        // Add the path to the validated data array
        $validatedData['company_logo'] = $path;
    }

    // Update with the validated data
    $job->update($validatedData);

    return redirect()->route('jobs.index')->with('success', 'Job listing updated successfully!');
    }

    public function destroy(Job $job): RedirectResponse
    {
        // Check if the user is authorized
        $this->authorize('delete', $job);
        // Delete the company logo from storage if it exists
        if ($job->company_logo) {
            Storage::delete('public/logos/' . basename($job->company_logo));
        }

        // Delete the job listing
        $job->delete();

        if (request()->query('from') === 'dashboard') {
        return redirect()->route('dashboard')->with('success', 'Job listing deleted successfully!');
        
        }
        return redirect()->route('jobs.index')->with('success', 'Job listing deleted successfully!');
    }
    // @desc  Log in user
    // @route POST /authenticate
    // public function authenticate(Request $request): string {
    // return 'authenticate';
    // }
    // @desc   Search for jobs
    // @route  GET /jobs/search
    public function search(Request $request): View
    {
    $keywords = strtolower($request->input('keywords'));
    $location = strtolower($request->input('location'));

    $query = Job::query();

    if ($keywords) {
        $query->where(function ($q) use ($keywords) {
            $q->whereRaw('LOWER(title) like ?', ['%' . $keywords . '%'])
                ->orWhereRaw('LOWER(description) like ?', ['%' . $keywords . '%']);
        });
    }

    if ($location) {
        $query->where(function ($q) use ($location) {
            $q->whereRaw('LOWER(address) like ?', ['%' . $location . '%'])
                ->orWhereRaw('LOWER(city) like ?', ['%' . $location . '%'])
                ->orWhereRaw('LOWER(state) like ?', ['%' . $location . '%'])
                ->orWhereRaw('LOWER(zipcode) like ?', ['%' . $location . '%']);
        });
    }

    $jobs = $query->paginate(12);

    return view('jobs.index')->with('jobs', $jobs);
    }
}
