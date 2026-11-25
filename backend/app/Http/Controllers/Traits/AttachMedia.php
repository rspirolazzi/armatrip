<?php
/**
 * Created by PhpStorm.
 * User: rodrigos
 * Date: 2019-08-18
 * Time: 1:09 p.m.
 */

namespace App\Http\Controllers\Traits;


use App\Exceptions\InvalidUploadException;
use Flash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Optix\Media\MediaUploader;
use Optix\Media\Models\Media;

trait AttachMedia
{
    protected function uploadImage($request, $inputName, $model, $group = 'image', $extensions = ['jpg', 'jpeg', 'png', 'gif'])
    {
        try {
            $file = $request->file($inputName);
            if ($file) {
                $this->validateImage($file, $extensions);
                Log::info('Se intentara subir la imagen', ['name' => $file->getClientOriginalName()]);
                Log::info('Para el modelo ', ['model' => class_basename($model), 'id' => $model->id]);
                $media = MediaUploader::fromFile($file)
                    ->useName($model->name)
                    ->upload();
                $model->attachMedia($media, $group);
                Log::info('Se subio con éxito la imagen', ['name' => $file->getClientOriginalName()]);

                // La imagen anterior se borra recién cuando la nueva quedó guardada.
                $this->deleteMedia($request->get($inputName . '_media_id'));
            } else {
                Log::debug('No hay imagen para subir para ', ['model' => class_basename($model), 'id' => $model->id]);
            }
            $this->deleteMedia($request->get('_delete_' . $inputName));
        } catch (InvalidUploadException $e) {
            Flash::error($e->getMessage());
        } catch (\Exception $e) {
            Log::error('Ocurrio un error al intentar procesar la imagen', ['message' => $e->getMessage(), 'e' => $e->getTraceAsString()]);
            Flash::error('No se pudo guardar la imagen. El resto de los datos sí se guardó.');
        }
    }

    /**
     * @throws InvalidUploadException
     */
    protected function uploadMultimediaPhotos($request, $inputName, $model, $group = 'photos')
    {
        $file = $request->file($inputName);
        if (!$file) {
            throw new InvalidUploadException('No se recibió ninguna imagen.');
        }
        $this->validateImage($file);
        $media = MediaUploader::fromFile($file)
            ->useName($file->getClientOriginalName())
            ->upload();
        $model->attachMedia($media, $group);
        Log::info('Se subio con éxito la foto multimedia', ['name' => $file->getClientOriginalName(), 'id' => $model->id]);
    }

    /**
     * @throws InvalidUploadException
     */
    private function validateImage(UploadedFile $file, $extensions = ['jpg', 'jpeg', 'png', 'gif'])
    {
        // Los mensajes flash se imprimen sin escapar.
        $name = e($file->getClientOriginalName());
        if (in_array($file->getError(), [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
            $max = floor(UploadedFile::getMaxFilesize() / 1024 / 1024);
            throw new InvalidUploadException("La imagen {$name} es muy pesada: el máximo es {$max} MB.");
        }
        if (!$file->isValid()) {
            throw new InvalidUploadException("La imagen {$name} no se pudo subir. Probá de nuevo.");
        }
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $extensions, true) || !in_array($file->getMimeType(), $this->imageMimeTypes($extensions), true)) {
            $formats = strtoupper(implode(', ', array_diff($extensions, ['jpeg'])));
            throw new InvalidUploadException("El archivo {$name} no es una imagen válida ({$formats}).");
        }
    }

    private function imageMimeTypes(array $extensions)
    {
        return array_map(function ($extension) {
            return 'image/' . ($extension === 'jpg' ? 'jpeg' : $extension);
        }, $extensions);
    }

    private function deleteMedia($id)
    {
        $media = $id ? Media::find($id) : null;
        if ($media) {
            $media->delete();
        }
    }

    protected function deleteImage($request, $inputName)
    {
        $filename = null;
        try {
            if ($request->get('_delete_' . $inputName, false)) {
                $filename = $request->get('_delete_' . $inputName, '');
                if (file_exists($filename)) {
                    Storage::delete($filename);
                    Log::info('Se borro el archivo' . $filename);
                }
                $filename = null;
            }
            $file = $request->file($inputName);
            if ($file) {
                $filename = Storage::putFile('public' . DIRECTORY_SEPARATOR . $directory_suffix, $file);
                $filename = str_replace('public' . DIRECTORY_SEPARATOR, '', $filename);
                Log::info('Se subio el archivo ' . $filename);
            }
        } catch (\Exception $e) {
            Log::error('Ocurrio un error al intentar procesar la imagen', ['e' => $e->getTraceAsString(),]);
            //dd($e);
            $filename = null;
        }
        return $filename;
    }
}