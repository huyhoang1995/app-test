<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Validator;

class FileCtrl extends Controller
{


    public function getContentFile(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'file' => 'required',
            'app_env' => 'required',
            'contract_server' => 'required',
        ], [
            'file.required' => 'file không được bỏ trống',
            'app_env.required' => 'app_env không được bỏ trống',
            'contract_server.required' => 'contract_server không được bỏ trống',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->messages(), 422);
        }
        $envArray = [
            0 => 'AWS',
            1 => 'K5',
            2 => 'T2',
        ];
        $serverArray = [
            0 => 'app1',
            1 => 'app2',
        ];

        $file = $request->file;
        $app_env = $request->app_env;
        $contract_server = $request->contract_server;
        $folderPath = public_path(str_replace('/', '\\', $envArray[$app_env] . '/' . $serverArray[$contract_server] . '/' . $file));

        if (file_exists($folderPath)) {


            $fileContent = base64_encode(file_get_contents($folderPath));
            return response()->json([
                'success' => true,
                'filename' => $file,
                'content' => $fileContent,
                'message' => "Seal Info response successfully"
            ], 500);
        } else {
            return response()->json([
                'success' => false,
                'FileName' => "",
                'message' => "Seal Info response false"
            ], 500);

        }

    }

    /**
     * 
     */
    public function compareFolders(Request $request)
    {
        $folderOneRelativePath = 'folder_one';
        $folderTwoRelativePath = 'folder_two';

        $folderOnePath = public_path($folderOneRelativePath);
        $folderTwoPath = public_path($folderTwoRelativePath);

        if (!file_exists($folderOnePath) || !file_exists($folderTwoPath)) {
            // Handle the case where one or both folders do not exist
            return response()->json(['error' => 'One or both folders do not exist'], 404);
        }

        $filesInFolderOne = scandir($folderOnePath);
        $filesInFolderTwo = scandir($folderTwoPath);

        $filesInFolderOne = array_diff($filesInFolderOne, ['.', '..']);
        $filesInFolderTwo = array_diff($filesInFolderTwo, ['.', '..']);

        $commonFileNames = array_intersect($filesInFolderOne, $filesInFolderTwo);

        // Convert the result to an array
        $commonFileNamesArray = array_values($commonFileNames);

        return $commonFileNamesArray;
    }
}