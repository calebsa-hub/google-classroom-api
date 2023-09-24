<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Google_Client;
use Google_Service_Classroom;
use Google_Service_Classroom_Course;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Exception;

class GoogleClassroomController extends Controller
{
    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleHandle()
    {
        try{
            $user = Socialite::driver('google')->user();
            session()->put('token', $user->token);
            session()->put('id', $user->id);
            session()->put('email', $user->email);

            return redirect('/');
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function createClassroom(Request $request)
    {
        $client = new Google_Client();
        $client->setApplicationName('API Laravel Classroom');
        $client->setScopes(Google_Service_Classroom::CLASSROOM_COURSES);
        $client->setAuthConfig(config_path('google_credencials2.json'));
        $client->setRedirectUri(env('GOOGLE_CLASSROOM_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        $refresh_token = env('REFRESH_TOKEN');

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refresh_token);
        }

        $service = new Google_Service_Classroom($client);

        $course = new Google_Service_Classroom_Course();

        $course->setId($request->id);
        $course->setName($request->name);
        $course->setSection($request->section);
        $course->setDescriptionHeading($request->descriptionHeading);
        $course->setDescription($request->description);
        $course->setRoom($request->room);
        $course->setOwnerId($request->ownerId);
        $course->setCourseState($request->courseState);

        $createdCourse = $service->courses->create($course);

        return response()->json([
            'message' => 'Turma criada com sucesso',
            'course' => $createdCourse,
        ]);
    }

    public function getCourse(Request $request, $id)
    {
        $client = new Google_Client();
        $client->setApplicationName('API Laravel Classroom');
        $client->setScopes(Google_Service_Classroom::CLASSROOM_COURSES);
        $client->setAuthConfig(config_path('google_credencials2.json'));
        $client->setRedirectUri(env('GOOGLE_CLASSROOM_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $refresh_token = env('REFRESH_TOKEN');

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refresh_token);

            $service = new Google_Service_Classroom($client);

            $curso = $service->courses->get($id);

            return response()->json([
                'curso' => $curso,
            ], 200);
        }else{
            return response()->json([
                'error' => 'Não foi possível salvar os registros'
            ], 500);
        }
    }

    public function listCourses(Request $request)
    {
        $client = new Google_Client();
        $client->setApplicationName('API Laravel Classroom');
        $client->setScopes(Google_Service_Classroom::CLASSROOM_COURSES);
        $client->setAuthConfig(config_path('google_credencials2.json'));
        $client->setRedirectUri(env('GOOGLE_CLASSROOM_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $refresh_token = env('REFRESH_TOKEN');

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refresh_token);
            $course_states = $request->courseStates;
            $page_size = $request->pageSize;
            $page_token = $request->pageToken;
            $student_id = $request->studentId;
            $teacher_id = $request->teacherId;


            $service = new Google_Service_Classroom($client);

            $courses = [];

            do {
                $params = [
                    'pageSize' => $page_size,
                    'pageToken' => $page_token,
                    'studentId' => $student_id,
                    'teacherId' => $teacher_id,
                    'courseStates' => $course_states,
                ];
                $response = $service->courses->listCourses($params);
                $courses = array_merge($courses, $response->courses);
                $page_token = $response->nextPageToken;
            } while (!empty($page_token));

            return response()->json([
                'courses' => $courses,
                'nextPageToken' =>$page_token,
            ], 200);
        }else{
            return response()->json([
                'error' => 'Não foi possível acessar os cursos'
            ], 500);
        }
    }

    public function updateCourse(Request $request, $id)
    {
        $client = new Google_Client();
        $client->setApplicationName('API Laravel Classroom');
        $client->setScopes(Google_Service_Classroom::CLASSROOM_COURSES);
        $client->setAuthConfig(config_path('google_credencials2.json'));
        $client->setRedirectUri(env('GOOGLE_CLASSROOM_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $refresh_token = env('REFRESH_TOKEN');

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refresh_token);

            $service = new Google_Service_Classroom($client);

            $course = $service->courses->get($id);

            $course->id = updateIfChanged($course, 'id', $request->id);
            $course->name = updateIfChanged($course, 'name', $request->name);
            $course->section = updateIfChanged($course, 'section', $request->section);
            $course->descriptionHeading = updateIfChanged($course, 'descriptionHeading', $request->descriptionHeading);
            $course->description = updateIfChanged($course, 'description', $request->description);
            $course->room = updateIfChanged($course, 'room', $request->room);
            $course->ownerId = updateIfChanged($course, 'ownerId', $request->ownerId);
            $course->courseState = updateIfChanged($course, 'courseState', $request->courseState);

            $course = $service->courses->update($id, $course);

            return response()->json([
                'course' => $course,
            ], 200);
        }else{
            return response()->json([
                'error' => 'Falha de autenticação'
            ], 500);
        }
    }

    public function patchCourse(Request $request, $id)
    {
        $client = new Google_Client();
        $client->setApplicationName('API Laravel Classroom');
        $client->setScopes(Google_Service_Classroom::CLASSROOM_COURSES);
        $client->setAuthConfig(config_path('google_credencials2.json'));
        $client->setRedirectUri(env('GOOGLE_CLASSROOM_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $refresh_token = env('REFRESH_TOKEN');

        $service = new Google_Service_Classroom($client);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refresh_token);
            $course = new Google_Service_Classroom_Course([
                'id' => $request->id,
                'name' => $request->name,
                'section' => $request->section,
                'descriptionHeading' => $request->descriptionHeading,
                'description' => $request->description,
                'room' => $request->room,
                'ownerId' => $request->ownerId,
                'courseState' => $request->courseState,
            ]);
            $params = ['updateMask' => $request->updateMask];
            $course = $service->courses->patch($id, $course, $params);

            return response()->json([
                'course' => $course,
            ], 200);
        }else{
            return response()->json([
                'error' => 'Falha de autenticação'
            ], 500);
        }

    }

    public function deleteCourse(Request $request, $id)
    {
        $client = new Google_Client();
        $client->setApplicationName('API Laravel Classroom');
        $client->setScopes(Google_Service_Classroom::CLASSROOM_COURSES);
        $client->setAuthConfig(config_path('google_credencials2.json'));
        $client->setRedirectUri(env('GOOGLE_CLASSROOM_REDIRECT_URI'));
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');
        $refresh_token = env('REFRESH_TOKEN');

        $accessToken = env('GOOGLE_ACCESS_TOKEN');

        $client->setAccessToken($accessToken);

        if($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($refresh_token);
        }

        if($client->getAccessToken()){
            $service = new Google_Service_Classroom($client);
            $course = $service->courses->get($id);

            if($course->courseState != 'ARCHIVED'){
                return response()->json([
                    'message' => 'Erro ao excluir o curso: ',
                ],400);
            }else{
                $service->courses->delete($id);

                return response()->json([
                    'message' => 'Curso excluído com sucesso!'
                ],200);
            }
        } else {
            return response()->json([
                'error' => 'Falha de autenticação'
            ], 500);
        }
    }
}
