<?php
namespace App\Traits\Order;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Request;
use App\Exceptions\OutputServerMessageException;
use App\Exports\PurchaseOrderExport;
use App\Exports\QuotationListExport;
use Excel;

trait Handle
{
    public function downloadPurchaseOrder(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'];
        $name = '采购表'.date('YmdHis').'.xlsx';
        return Excel::download(new PurchaseOrderExport($ids), $name);
    }
    public function downloadQuotationList(Request $request)
    {
        $data = $request->all();
        $ids = $data['ids'];
        $name = '报价表'.date('YmdHis').'.xlsx';
        return Excel::download(new QuotationListExport($ids), $name);
    }
    //付款
    public function pay(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);
            $payment = $this->paymentRepository->find($attributes['payment_id']);
            if($order->pay_status == 'paid')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'pay_status' => 'paid',
                'payment_id' => $payment->id,
                'payment_name' => $payment->name,
                'payment_sn' => $attributes['payment_sn'],
                'paid_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->response->message(trans('messages.operation.success'))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('order'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('order'))
                ->redirect();
        }
    }
    //发货
    public function toDelivery(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);

            if($order->shipping_status == 'shipped')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'shipping_status' => 'shipped',
                'shipped_at' => date('Y-m-d H:i:s'),
                'tracking_number' => $attributes['tracking_number']
            ]);

            return $this->response->message(trans('messages.operation.success'))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('order'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('order'))
                ->redirect();
        }
    }
    //取消
    public function cancel(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);

            if($order->shipping_status == 'shipped')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'order_status' => 'cancelled',
                'pay_status' => 'refunded',
            ]);

            return $this->response->message(trans('messages.operation.success'))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('order'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('order'))
                ->redirect();
        }
    }
    //收获
    public function receive(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);

            if($order->shipping_status != 'shipped')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'shipping_status' => 'received',
            ]);

            return $this->response->message(trans('messages.operation.success'))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('order'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('order'))
                ->redirect();
        }
    }
    public function returnOrder(Request $request)
    {
        try {
            $attributes = $request->all();
            $id = $attributes['id'];
            $order = $this->repository->find($id);

            if($order->shipping_status != 'received' && $order->shipping_status != 'shipped')
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $order->update([
                'order_status' => 'returned',
                'shipping_status' => 'returned',
                'pay_status' => 'refunded'
            ]);

            return $this->response->message(trans('messages.operation.success'))
                ->status("success")
                ->http_code(202)
                ->url(guard_url('order'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('order'))
                ->redirect();
        }
    }
}