<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Hash;

class AccountCtrl extends Controller
{
    private $account;

    //
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
    public function listAccount(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'page' => 'required|numeric',
            'perPage' => 'required|numeric',
        ], [
            'page.required' => 'page không được bỏ trống',
            'page.numeric' => 'page không đúng định dạng',
            'perPage.required' => 'perPage không được bỏ trống',
            'perPage.numeric' => 'perPage không đúng định dạng',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }

        $perPage = $request->input('perPage', 10);
        $freeText = $request->input('freeText', '');
        $listAccount = $this->account->search($freeText)->paginate($perPage);

        return response()->json($listAccount);
    }

    public function detailAccount($id)
    {
        $account = $this->account->find($id);
        return response()->json($account);
    }

    public function createAccount(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required',
            'phone' => 'required'
        ], [
            'login.required' => 'login không được bỏ trống',
            'password.required' => 'password không được bỏ trống',
            'phone.required' => 'phone không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }

        $newId = $this->account->insertGetId([
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'created_at' => Date('Y-m-d H:i:s'),
            'updated_at' => Date('Y-m-d H:i:s'),
        ]);


        return response()->json(['status' => true, 'id' => $newId]);
    }
    public function updateAccount(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required',
            'phone' => 'required'
        ], [
            'login.required' => 'Login không được bỏ trống',
            'password.required' => 'Password không được bỏ trống',
            'phone.required' => 'Phone không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }

        // Find the account you want to update by its ID
        $account = $this->account->find($id);

        if (!$account) {
            return response()->json(['status' => false, 'message' => 'Account not found'], 404);
        }

        $hashedPassword = Hash::make($request->password);

        // Update the account fields
        $account->login = $request->login;
        $account->password = $hashedPassword;
        $account->phone = $request->phone;
        $account->updated_at = now(); // You can use the now() function to get the current timestamp

        // Save the changes to the database
        $account->save();

        return response()->json(['status' => true, 'message' => 'Account updated successfully']);
    }

    public function deleteAccount($id)
    {
        // get account
        $account = $this->account->find($id);

        if (!$account) {
            return response()->json(['status' => false, 'message' => 'Account not found'], 404);
        }

        // Delete the account
        $account->delete();

        return response()->json(['status' => true, 'message' => 'Account deleted successfully']);
    }
}