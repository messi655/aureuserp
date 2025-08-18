<?php

namespace Webkul\Invoice\Filament\Clusters\Vendors\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Webkul\Account\Filament\Resources\PaymentsResource as BasePaymentsResource;
use Webkul\Invoice\Filament\Clusters\Vendors;
use Webkul\Invoice\Filament\Clusters\Vendors\Resources\PaymentsResource\Pages;
use Webkul\Invoice\Models\Payment;

class PaymentsResource extends BasePaymentsResource
{
    protected static ?string $model = Payment::class;

    protected static bool $shouldRegisterNavigation = true;

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Vendors::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getModelLabel(): string
    {
        return __('invoices::filament/clusters/vendors/resources/payment.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/vendors/resources/payment.navigation.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return null;
    }

    public static function form(Form $form): Form
    {
        $form = parent::form($form);

        $components = $form->getComponents();

        $group = $components[1]?->getChildComponents()[0] ?? null;

        if ($group) {
            $fields = $group->getChildComponents();

            $fields[1] = $fields[1]->label(__('invoices::filament/resources/payment.form.sections.fields.vender-bank-account'));

            $fields[2] = Forms\Components\Select::make('partner_id')
                ->label(__('invoices::filament/resources/payment.form.sections.fields.vender'))
                ->relationship(
                    'partner',
                    'name',
                    fn ($query) => $query->where('sub_type', 'supplier')->orderBy('id')
                )
                ->searchable()
                ->preload();

            $group->childComponents($fields);
            $components[1]->childComponents([$group]);
        }

        return $form->components($components);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $infolist = parent::infolist($infolist);

        $components = $infolist->getComponents();

        $group = $components[0]?->getChildComponents()[0] ?? null;

        if ($group) {
            $fields = $group->getChildComponents();

            $fields[2] = $fields[2]->label(__('invoices::filament/resources/payment.form.sections.fields.vender-bank-account'));
            $fields[3] = $fields[3]->label(__('invoices::filament/resources/payment.form.sections.fields.vender'));

            $group->childComponents($fields);
            $components[0]->childComponents([$group]);
        }

        return $infolist->components($components);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayments::route('/create'),
            'view'   => Pages\ViewPayments::route('/{record}'),
            'edit'   => Pages\EditPayments::route('/{record}/edit'),
        ];
    }
}
