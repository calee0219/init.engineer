<?php

namespace App\Http\Livewire\Backend;

use App\Domains\Social\Models\Platform;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\TableComponent;
use Rappasoft\LaravelLivewireTables\Traits\HtmlComponents;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class SocialPlatformTable.
 */
class SocialPlatformTable extends TableComponent
{
    use HtmlComponents;

    /**
     * @var string
     */
    public $sortField = 'name';

    /**
     * @var string
     */
    public $status;

    /**
     * @var array
     */
    protected $options = [
        'bootstrap.container' => false,
        'bootstrap.classes.table' => 'table table-striped',
    ];

    /**
     * @param  string  $status
     */
    public function mount($status = 'active'): void
    {
        $this->status = $status;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        if ($this->status === 'deleted') {
            return Platform::onlyTrashed();
        }

        if ($this->status === 'deactivated') {
            return Platform::where('active', false);
        }

        return Platform::where('active', true);
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            Column::make(__('Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make(__('Type'), 'type')
                ->format(function(Platform $model) {
                    return $this->html(view('backend.social.platform.includes.type', ['platform' => $model]));
                })
                ->searchable()
                ->sortable(),
            Column::make(__('Active'), 'active')
                ->format(function(Platform $model) {
                    return $this->html(view('backend.social.platform.includes.active', ['platform' => $model]));
                })
                ->searchable()
                ->sortable(),
            Column::make(__('Actions'))
                ->format(function (Platform $model) {
                    return view('backend.social.platform.includes.actions', ['platform' => $model]);
                }),
        ];
    }
}