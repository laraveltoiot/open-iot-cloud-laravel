<?php declare(strict_types=1);

namespace App\Livewire\TokenManagement;

use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Component;

/**
 * @property-read array $abilities
 */
final class ApiTokenManager extends Component
{
    protected $listeners = ['refresh' => '$refresh'];

    public $tokens;

    public $createForm = ['name' => '', 'permissions' => []];

    public $updateForm = ['permissions' => []];

    public $displayToken = false;

    public $plainTextToken;

    public $managingPermissions = false;

    public $confirmingDeletion = false;

    public $selectedToken;

    public function mount(): void
    {
        $this->tokens = Auth::user()->tokens;
        $this->createForm['permissions'] = ['read'];
    }

    public function createToken(): void
    {
        $this->validate([
            'createForm.name' => 'required|string|max:255',
            'createForm.permissions' => 'array|min:1',
        ]);

        $token = Auth::user()->createToken(
            $this->createForm['name'],
            $this->createForm['permissions']
        );

        $this->plainTextToken = explode('|', $token->plainTextToken, 2)[1];
        $this->displayToken = true;
        $this->createForm = ['name' => '', 'permissions' => array_keys($this->abilities)];
        $this->tokens = Auth::user()->tokens()->get();
    }

    public function managePermissions($tokenId): void
    {
        $this->selectedToken = PersonalAccessToken::find($tokenId);
        $this->updateForm['permissions'] = $this->selectedToken->abilities;
        $this->managingPermissions = true;
    }

    public function updatePermissions(): void
    {
        $this->selectedToken->forceFill(['abilities' => $this->updateForm['permissions']])->save();
        $this->managingPermissions = false;
        $this->tokens = Auth::user()->tokens()->get();
    }

    public function confirmDeletion($tokenId): void
    {
        $this->selectedToken = $tokenId;
        $this->confirmingDeletion = true;
    }

    public function deleteToken(): void
    {
        Auth::user()->tokens()->where('id', $this->selectedToken)->delete();
        $this->confirmingDeletion = false;
        $this->tokens = Auth::user()->tokens()->get();
    }

    public function getAbilitiesProperty(): array
    {
        return [
            'create' => 'Create',
            'read' => 'Read',
            'update' => 'Update',
            'delete' => 'Delete',
        ];
    }

    public function render()
    {
        return view('livewire.token-management.api-token-manager');
    }
}
