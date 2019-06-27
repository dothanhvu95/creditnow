<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CicLog extends Model
{
    public $table = 'cic_logs';
    public $timestamps=true;
    protected $fillable = [
         'phone_id', 'check_time', 'cid_result', 'cid_code', 'cid_customer_name', 'publiser_id', 'cid_customer_address', 'address1', 'address2', 'cid_customer_tell', 'gender', 'email', 'cmnd', 'phone', 'age', 'married', 'child', 'job', 'name', 'giay_to_ca_nhan', 'giay_to_cong_viec', 'bank', 'thong_tin_no', 'nguoi_hon_phoi', 'verify_code', 'income', 'rent_money', 'cib_point', 'point_declare', 'cic_reload', 'cic_last_result', 'cib_random_point', 'cib_last_random_point', 'self_declare_point', 'final_score', 'loan', 'interest_rate', 'duration', 'referal_id', 'api_price', 'money_left', 'user_id', 'api_key', 'status', 'allow', 'check_flow', 'check_admin', 'note', 'admin_note', 'email_code', 'progress_info', 'self_declare_info', 'cs_name', 'cs_id', 'tctd_id', 'wb_id', 'debt'
    ];
}
