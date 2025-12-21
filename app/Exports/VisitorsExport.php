<?php

namespace App\Exports;

use App\Models\Visitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitorsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
        $query = Visitor::query()->with(['city.uf', 'user']);

        // Aplicar filtros se existirem
        if (isset($this->filters['search']) && $this->filters['search']) {
            $query->where('name', 'like', '%' . $this->filters['search'] . '%');
        }

        if (isset($this->filters['startDate']) && $this->filters['startDate']) {
            $query->where('visit_date', '>=', $this->filters['startDate']);
        }

        if (isset($this->filters['endDate']) && $this->filters['endDate']) {
            $query->where('visit_date', '<=', $this->filters['endDate']);
        }

        return $query->orderBy('visit_date', 'desc')->orderBy('name')->get();
    }

    /**
     * Cabeçalhos das colunas
     */
    public function headings(): array
    {
        return [
            'Nome',
            'Data da Visita',
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
            'Aceita Mensagens',
            'Cadastrado Por',
            'Observações'
        ];
    }

    /**
     * Mapear os dados para cada linha
     */
    public function map($visitor): array
    {
        return [
            $visitor->name,
            $visitor->visit_date ? \Carbon\Carbon::parse($visitor->visit_date)->format('d/m/Y') : '',
            $visitor->birth_date ? \Carbon\Carbon::parse($visitor->birth_date)->format('d/m/Y') : '',
            $visitor->gender == 'M' ? 'Masculino' : ($visitor->gender == 'F' ? 'Feminino' : ''),
            $visitor->marital_status ?? '',
            $visitor->mail ?? '',
            $visitor->mobile_phone ? $this->formatPhone($visitor->mobile_phone) : '',
            $visitor->landline_phone ? $this->formatPhone($visitor->landline_phone) : '',
            $visitor->profession ?? '',
            $visitor->education_level ?? '',
            $visitor->address ?? '',
            $visitor->number ?? '',
            $visitor->neighborhood ?? '',
            $visitor->complement ?? '',
            $visitor->zip_code ? $this->formatZipCode($visitor->zip_code) : '',
            $visitor->city ? $visitor->city->name : '',
            $visitor->city && $visitor->city->uf ? $visitor->city->uf->uf : '',
            $visitor->accept_receive_messages ? 'Sim' : 'Não',
            $visitor->user ? $visitor->user->name : '',
            $visitor->observations ?? ''
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
