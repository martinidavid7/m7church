<?php

namespace App\Exports;

use App\Models\Person;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PersonsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Person::query()->with(['city.uf']);

        // Aplicar filtros se existirem
        if (isset($this->filters['name']) && $this->filters['name']) {
            $query->where('name', 'like', '%' . $this->filters['name'] . '%');
        }

        if (isset($this->filters['active']) && $this->filters['active'] !== '') {
            $query->where('active', $this->filters['active']);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Cabeçalhos das colunas
     */
    public function headings(): array
    {
        return [
            'Nome',
            'Data de Nascimento',
            'Sexo',
            'Estado Civil',
            'Email',
            'Telefone Celular',
            'Telefone Fixo',
            'Profissão',
            'Escolaridade',
            'Endereço',
            'Número',
            'Bairro',
            'Complemento',
            'CEP',
            'Cidade',
            'UF',
            'Data de Batismo',
            'Data de Membresia',
            'Status',
            'Observações'
        ];
    }

    /**
     * Mapear os dados para cada linha
     */
    public function map($person): array
    {
        return [
            $person->name,
            $person->birth_date ? \Carbon\Carbon::parse($person->birth_date)->format('d/m/Y') : '',
            $person->gender == 'M' ? 'Masculino' : ($person->gender == 'F' ? 'Feminino' : ''),
            $person->marital_status ?? '',
            $person->mail,
            $person->mobile_phone ? $this->formatPhone($person->mobile_phone) : '',
            $person->landline_phone ? $this->formatPhone($person->landline_phone) : '',
            $person->profession ?? '',
            $person->education_level ?? '',
            $person->address ?? '',
            $person->number ?? '',
            $person->neighborhood ?? '',
            $person->complement ?? '',
            $person->zip_code ? $this->formatZipCode($person->zip_code) : '',
            $person->city ? $person->city->name : '',
            $person->city && $person->city->uf ? $person->city->uf->uf : '',
            $person->baptism_date ? \Carbon\Carbon::parse($person->baptism_date)->format('d/m/Y') : '',
            $person->membership_date ? \Carbon\Carbon::parse($person->membership_date)->format('d/m/Y') : '',
            $person->active ? 'Ativo' : 'Inativo',
            $person->observations ?? ''
        ];
    }

    /**
     * Formatar telefone
     */
    private function formatPhone($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) == 11) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
        } elseif (strlen($phone) == 10) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
        }

        return $phone;
    }

    /**
     * Formatar CEP
     */
    private function formatZipCode($zipCode)
    {
        $zipCode = preg_replace('/[^0-9]/', '', $zipCode);

        if (strlen($zipCode) == 8) {
            return substr($zipCode, 0, 5) . '-' . substr($zipCode, 5);
        }

        return $zipCode;
    }

    /**
     * Estilizar a planilha
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Estilizar o cabeçalho
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'],
                    'bold' => true
                ]
            ],
        ];
    }
}
