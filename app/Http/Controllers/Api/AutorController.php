<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AutorController extends Controller
{


    /**
     * Metodo para ver todos los autores
     * Request GET
     */
    public function show()
    {
        $authors = Autor::all();

        if ($authors->isEmpty()) {
            $data = [
                'message' => 'No se encontraron autores',
                'autores' => $authors,
                'status' => Response::HTTP_OK
            ];
            return response()->json($data, Response::HTTP_OK);
        }
        $data = [
            'message' => 'Se encontraron resultados',
            'autores' => $authors,
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Metodo para guardar un autor
     * Request POST
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:150|unique:autores,nombre',
            'fecha_nacimiento' => 'required|date_format:Y-m-d',
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en validacion de los datos enviados',
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }

        $author = new Autor();
        $author->nombre = $request->nombre;
        $author->fecha_nacimiento = $request->fecha_nacimiento;
        $author->save();

        $data = [
            'message' => 'Datos guardados correctamente',
            'data' => $author,
            'status' => Response::HTTP_CREATED
        ];

        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
     * Metodo para buscar el autor por id
     * Request GET
     */
    public function showId($id)
    {
        $author = Autor::find($id);

        if (!$author) {
            $data = [
                'message' => 'No se encontro el autor',
                'autores' => $author,
                'status' => Response::HTTP_NOT_FOUND
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
        $data = [
            'autores' => $author,
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Metodo para actualizar un autor por id
     * Request PUT
     */
    public function update(Request $request, string $id)
    {
        $author = Autor::find($id);

        if (!$author) {
            $data = [
                'message' => 'No se encontro el autor para actualizar',
                'autores' => $author,
                'status' => Response::HTTP_NOT_FOUND
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:150',
            'fecha_nacimiento' => 'required|date_format:Y-m-d',
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en validacion de los datos enviados',
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }

        $author->nombre = $request->nombre;
        $author->fecha_nacimiento = $request->fecha_nacimiento;
        $author->save();

        $data = [
            'message' => 'Datos actualizados correctamente',
            'data' => $author,
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Metodo para actualizar un autor por campo especifico
     * Request PUT
     */
    public function updatePartial(Request $request, string $id)
    {
        $author = Autor::find($id);

        if (!$author) {
            $data = [
                'message' => 'No se encontro el autor para actualizar',
                'autores' => $author,
                'status' => Response::HTTP_NOT_FOUND
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'max:150|unique:autores,nombre',
            'fecha_nacimiento' => 'date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en validacion de los datos enviados',
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }

        if ($request->has('nombre')) {
            $author->nombre = $request->nombre;
        }
        if ($request->has('fecha_nacimiento')) {
            $author->fecha_nacimiento = $request->fecha_nacimiento;
        }

        $author->save();


        $data = [
            'message' => 'Datos actualizados correctamente',
            'data' => $author,
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Metodo para eliminar un autor por id
     * Request DELETE
     */
    public function destroy(string $id)
    {
        $author = Autor::find($id);

        if (!$author) {
            $data = [
                'message' => 'No se encontro el autor',
                'autores' => $author,
                'status' => Response::HTTP_NOT_FOUND
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }


        $author->destroy($id);
        $data = [
            'message' => 'Se elimino el autor',
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);
    }



}
