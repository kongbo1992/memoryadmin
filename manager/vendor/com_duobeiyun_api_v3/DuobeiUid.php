<?php

namespace com_duobeiyun_api_v3;
class DuobeiUid{

    public function gen_student_uuid($userid){
        return $this->gen_uuid(2, $userid);
    }

    public function gen_teacher_uuid($auserid){
        return $this->gen_uuid(1, $auserid);
    }

    public function gen_assistant_uuid(){
        return $this->gen_uuid(4, mt_rand( 0, 0xffff ));
    }

    public function gen_uuid($type,$userid) {
        return sprintf( '%x-%x',$type,$userid);
    }
}