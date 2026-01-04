<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiswaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nis' => $this->nis,
            'nama_siswa' => $this->nama_siswa,
            'jenis_kelamin' => $this->jenis_kelamin,
            'kelas' => $this->whenLoaded('kelas', function () {
                return [
                    'id' => $this->kelas?->id,
                    'nama_kelas' => $this->kelas?->nama_kelas,
                ];
            }),
        ];
    }
}
