<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Crea a nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Metodo para logearse.
     *
     * @return JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            $data = [
                'message' => 'No se encuentra autorizado para ingresar al sistema.',
                'status' => Response::HTTP_UNAUTHORIZED
            ];
            return response()->json($data, Response::HTTP_UNAUTHORIZED);


        }

        return $this->respondWithToken($token);
    }

    /**
     * Metodo para ver todos los usuarios
     *
     * @return JsonResponse
     */
    public function showUsers()
    {
        $data = [
            'message' => 'Se encontraron resultados',
            'usuarios' => auth()->user(),
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);

    }

    /**
     * Metodo para destruir token y cerrar sesion
     *
     * @return JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        $data = [
            'message' => 'Sesion cerrada correctamente',
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);

    }

    /**
     * Metodo para refrescar el token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Metodo para definir la estructura del token {token, tipo, tiempo expiracion}
     *
     * @param  string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        $expires = auth()->factory()->getTTL() * 60;
        $ttl_minutes = auth()->factory()->getTTL();
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in_seg' => $expires,
            'expires_in_min' => $ttl_minutes,
            'status' => Response::HTTP_OK
        ];
        return response()->json($data, Response::HTTP_OK);

    }

    /**
     * Metodo para registrar un usuario
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
           // return response()->json($validator->errors()->toJson(),400);

            $data = [
                'message' => 'Error en validacion de los datos para crear un usuario',
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);

        }


        $user = User::create(array_merge(
            $validator->validate(),
            ['password' => bcrypt($request->password)]
        ));


        $data = [
            'message' => '¡Usuario registrado exitosamente!',
            'user' => $user,
            'status' => Response::HTTP_CREATED
        ];
        return response()->json($data, Response::HTTP_CREATED);

    }
}
