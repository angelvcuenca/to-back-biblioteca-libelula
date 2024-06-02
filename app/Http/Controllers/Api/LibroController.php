<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Autor;
use App\Models\Libro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class LibroController extends Controller
{
    /**
     * Metodo para ver todos los libros
     * Request: GET
     */
    public function show()
    {
        //$books = Libro::all();
        $books = Libro::with('autor')->get();

        $data = [
            'libros' => $books,
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Metodo para buscar el libro por id
     * Request GET
     */
    public function showId($id)
    {
        $books = Libro::with('autor')->find($id);
        // $books = Libro::all();

        if (!$books) {
            $data = [
                'message' => 'No se encontraron libros',
                'libros' => $books,
                'status' => Response::HTTP_OK
            ];
            return response()->json($data, Response::HTTP_OK);
        }
        $data = [
            'message' => 'Se encontraron resultados',
            'libros' => $books,
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Metodo para guardar un libro
     * Request POST
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|max:150|unique:libros,titulo',
            'anio_publicacion' => 'required|integer|digits:4',
            'descripcion' => 'required|max:150',
            'autor_ids' => 'required|array',
            'autor_ids.*' => 'required|string|distinct'
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en validacion de los datos enviados',
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        //$this->validateDataBook($request);
        $this->validateAuthorIds($request->autor_ids);

        $book = Libro::create($request->only('titulo', 'descripcion', 'anio_publicacion'));
        $book->autor()->sync($request->autor_ids);

        $data = [
            'message' => 'Datos guardados correctamente',
            'data' => $book->load('autor'),
            'status' => Response::HTTP_CREATED
        ];

        return response()->json($data, Response::HTTP_CREATED);
    }

    /**
     * Metodo para actualizar un autor por id
     * Request PUT
     */
    public function update(Request $request, string $id)
    {
        $book = Libro::find($id);

        if (!$book) {
            $data = [
                'message' => 'No se encontro el libro',
                'libros' => $book,
                'status' => Response::HTTP_NOT_FOUND
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
        $validator = Validator::make($request->all(), [
            'titulo' => 'max:150',
            'anio_publicacion' => 'integer|digits:4',
            'descripcion' => 'max:150',
            'autor_ids' => 'array',
            'autor_ids.*' => 'string|distinct'
        ]);
        if ($validator->fails()) {
            $data = [
                'message' => 'Error en validacion de los datos enviados',
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }
        $this->validateAuthorIds($request->autor_ids);

        $book->titulo = $request->titulo;
        $book->anio_publicacion = $request->anio_publicacion;
        $book->descripcion = $request->descripcion;
        $book->autor()->sync($request->autor_ids);
        $book->save();

        $data = [
            'message' => 'Datos actualizados correctamente',
            'data' => $book,
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Metodo para actualizar un autor por campo especifico
     * Request PUT
     * */
    public function updatePartial(Request $request, string $id)
    {
        $book = Libro::find($id);

        if (!$book) {
            $data = [
                'message' => 'No se encontro el libro',
                'libro' => $book,
                'status' => Response::HTTP_NOT_FOUND
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }
        $validator = Validator::make($request->all(), [
            'titulo' => 'max:150',
            'anio_publicacion' => 'integer|digits:4',
            'descripcion' => 'max:150',
            'autor_ids' => 'array',
            'author_ids.*' => 'exists:autor,_id'
        ]);

        if ($validator->fails()) {
            $data = [
                'message' => 'Error en validacion de los datos enviados',
                'errors' => $validator->errors(),
                'status' => Response::HTTP_BAD_REQUEST
            ];
            return response()->json($data, Response::HTTP_BAD_REQUEST);
        }



        if ($request->has('titulo')) {
            $book->titulo = $request->titulo;
        }
        if ($request->has('anio_publicacion')) {
            $book->anio_publicacion = $request->anio_publicacion;
        }
        if ($request->has('descripcion')) {
            $book->descripcion = $request->descripcion;
        }
        if ($request->has('autor_ids')) {
            $this->validateAuthorIds($request->autor_ids);
            $book->autor()->sync($request->autor_ids);
        }

        $book->save();


        $data = [
            'message' => 'Datos actualizados correctamente',
            'data' => $book,
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
        $book = Libro::find($id);
        if (!$book) {
            $data = [
                'message' => 'No se encontro el libro para eliminar',
                'libro  ' => $book,
                'status' => Response::HTTP_NOT_FOUND
            ];
            return response()->json($data, Response::HTTP_NOT_FOUND);
        }

        if (!is_null($book->autor_ids) && is_array($book->autor_ids)) {
            DB::collection('autores')->whereIn('_id', $book->autor_ids)->update([
                '$pull' => ['libro_ids' => $book->_id]
            ]);
        }

        $book->destroy($id);
        $data = [
            'message' => 'Se elimino el libro',
            'status' => Response::HTTP_OK
        ];

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Metodo para validar los _ids de los autores
     */
    public function validateAuthorIds($authorIds)
    {
        $existingAuthorIds = Autor::whereIn('_id', $authorIds)->pluck('_id')->toArray();
        if (count($authorIds) !== count($existingAuthorIds)) {
            throw ValidationException::withMessages([
                'autor_ids' => 'No existen autores con los _ids ingresados o estan duplicados los _ids'
            ]);
        }

    }


}
