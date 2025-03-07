<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment; 
use App\Models\Coupon;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
 
class AdminController extends Controller
{
  
  public function AdminDashboard()
    {
        // Statistiques utilisateurs
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalInstructors = User::where('role', 'instructor')->count();
        $totalStudents = User::where('role', 'user')->count();
        $newUsers = User::where('created_at', '>=', now()->subMonth())->count();

        // Statistiques cours
        $totalCourses = Course::count();
        $activeCourses = Course::where('status', 1)->count();
        $popularCourses = Course::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get()
            ->pluck('orders_count', 'course_name')
            ->toArray();

        // Statistiques commandes (via payments)
        $totalOrders = Payment::count();
        $pendingOrders = Payment::where('status', 'pending')->count();
        $monthlyRevenue = Payment::where('created_at', '>=', now()->subYear())
            ->selectRaw('order_month as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderByRaw('FIELD(month, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")')
            ->pluck('revenue', 'month')
            ->toArray();

        // Statistiques coupons
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('status', 1)->where('coupon_validity', '>=', now())->count();

        // Statistiques reviews
        $totalReviews = Review::count();
        $averageRating = Review::avg('rating') ?? 0;

        return view('admin.index', compact(
            'totalUsers', 'totalAdmins', 'totalInstructors', 'totalStudents', 'newUsers',
            'totalCourses', 'activeCourses', 'popularCourses',
            'totalOrders', 'pendingOrders', 'monthlyRevenue',
            'totalCoupons', 'activeCoupons',
            'totalReviews', 'averageRating'
        ));
    }

    // Nouvelle méthode pour les mises à jour dynamiques
    public function GetDynamicData()
    {
        $monthlyRevenue = Payment::where('created_at', '>=', now()->subYear())
            ->selectRaw('order_month as month, SUM(total_amount) as revenue')
            ->groupBy('month')
            ->orderByRaw('FIELD(month, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")')
            ->pluck('revenue', 'month')
            ->toArray();

        $popularCourses = Course::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($course) {
                return [
                    'course_name' => $course->course_name,
                    'orders_count' => $course->orders_count + rand(-5, 5), // Simulation de variation
                ];
            })->toArray();

        return response()->json([
            'monthlyRevenue' => $monthlyRevenue,
            'popularCourses' => $popularCourses,
        ]);
    }// End Method 

    public function AdminLogout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $notification = array(
            'message' => 'Logout Successfully',
            'alert-type' => 'info'
        );
 
        return redirect('/admin/login')->with($notification);
    } // End Method 

    public function AdminLogin(){
        return view('admin.admin_login');
    } // End Method 


    public function AdminProfile(){

        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile_view',compact('profileData'));
    }// End Method


    public function AdminProfileStore(Request $request){

        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        if ($request->file('photo')) {
           $file = $request->file('photo');
           @unlink(public_path('upload/admin_images/'.$data->photo));
           $filename = date('YmdHi').$file->getClientOriginalName();
           $file->move(public_path('upload/admin_images'),$filename);
           $data['photo'] = $filename; 
        }

        $data->save();

        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
        
    }// End Method

    public function AdminChangePassword(){

        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_change_password',compact('profileData'));

    }// End Method

 
    public function AdminPasswordUpdate(Request $request){

        /// Validation 
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed'
        ]);

        if (!Hash::check($request->old_password, auth::user()->password)) {
            
            $notification = array(
                'message' => 'Old Password Does not Match!',
                'alert-type' => 'error'
            );
            return back()->with($notification);
        } 

        /// Update The new Password 
        User::whereId(auth::user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        $notification = array(
            'message' => 'Password Change Successfully',
            'alert-type' => 'success'
        );
        return back()->with($notification); 

    }// End Method


    public function BecomeInstructor(){

        return view('frontend.instructor.reg_instructor');

    }// End Method

    public function InstructorRegister(Request $request){

        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required', 'string','unique:users'],
        ]);

        User::insert([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' =>  Hash::make($request->password),
            'role' => 'instructor',
            'status' => '0',
        ]);

        $notification = array(
            'message' => 'Instructor Registed Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('instructor.login')->with($notification); 

    }// End Method


    public function AllInstructor(){

        $allinstructor = User::where('role','instructor')->latest()->get();
        return view('admin.backend.instructor.all_instructor',compact('allinstructor'));
    }// End Method
 
    public function UpdateUserStatus(Request $request){

        $userId = $request->input('user_id');
        $isChecked = $request->input('is_checked',0);

        $user = User::find($userId);
        if ($user) {
            $user->status = $isChecked;
            $user->save();
        }

        return response()->json(['message' => 'User Status Updated Successfully']);

    }// End Method


    public function AdminAllCourse(){

        $course = Course::latest()->get();
        return view('admin.backend.courses.all_course',compact('course'));

    }// End Method


    public function UpdateCourseStatus(Request $request){

        $courseId = $request->input('course_id');
        $isChecked = $request->input('is_checked',0);

        $course = Course::find($courseId);
        if ($course) {
            $course->status = $isChecked;
            $course->save();
        }

        return response()->json(['message' => 'Course Status Updated Successfully']);

    }// End Method

    public function AdminCourseDetails($id){

        $course = Course::find($id);
        return view('admin.backend.courses.course_details',compact('course'));

    }// End Method

    /// Admin User All Method ////////////

    public function AllAdmin(){

        $alladmin = User::where('role','admin')->get();
        return view('admin.backend.pages.admin.all_admin',compact('alladmin'));

    }// End Method

    public function AddAdmin(){

        $roles = Role::all();
        return view('admin.backend.pages.admin.add_admin',compact('roles'));

    }// End Method

    public function StoreAdmin(Request $request){

        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->status = '1';
        $user->save();

        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        $notification = array(
            'message' => 'New Admin Inserted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification); 

    }// End Method


    public function EditAdmin($id){

        $user = User::find($id);
        $roles = Role::all();
        return view('admin.backend.pages.admin.edit_admin',compact('user','roles'));

    }// End Method

    public function UpdateAdmin(Request $request,$id){

        $user = User::find($id);
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address; 
        $user->role = 'admin';
        $user->status = '1';
        $user->save();

        $user->roles()->detach();
        if ($request->roles) {
            $user->assignRole($request->roles);
        }

        $notification = array(
            'message' => 'Admin Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.admin')->with($notification); 

    }// End Method

    public function DeleteAdmin($id){

        $user = User::find($id);
        if (!is_null($user)) {
            $user->delete();
        }

        $notification = array(
            'message' => 'Admin Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification); 

    }// End Method
    


}
 