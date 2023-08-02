<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('seller/transaction/order-list') ?'active':'' }}"><a href="{{route('seller.transaction.order-list')}}">{{\App\CPU\translate('Order_Transactions')}}</a></li>
        <li class="{{ Request::is('seller/transaction/expense-list') ?'active':'' }}"><a href="{{route('seller.transaction.expense-list')}}">{{\App\CPU\translate('Expense_Transactions')}}</a></li>
    </ul>
</div>
