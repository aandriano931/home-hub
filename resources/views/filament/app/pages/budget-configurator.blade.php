<x-filament-panels::page>
    <ul> 
        <li><strong>Nom du budget :</strong> {{$budget->label}}</li>
        <li><strong>Date d'expiration :</strong> {{ \Carbon\Carbon::parse($budget->expiration_date)->format('d/m/Y') }}</li>
        <li><strong>Total crédit :</strong> {{$totalCredit}} €</li>
        <li><strong>Total débit :</strong> {{$totalDebit}} €</li>
        <li><strong>Total :</strong> {{$totalDebit - $totalCredit}} €</li>
    </ul>
    @livewire('list-budget-lines', ['budget' => $budget])
    @livewire('list-budget-contributors', ['budget' => $budget, 'totalCredit' => $totalCredit, 'totalDebit' => $totalDebit])
</x-filament-panels::page>
