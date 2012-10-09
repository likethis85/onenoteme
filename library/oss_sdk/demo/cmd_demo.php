<?php
require_once '../sdk.class.php';

while(true){
	fwrite(STDOUT,"Please Choose Funcion No(1-18),Continue(19),Exit(0):\n");
	fwrite(STDOUT,"(0).Exit Function...\n");
	fwrite(STDOUT,"(1).Get Bucket List,list_bucket(\$options = NULL):\n");
	fwrite(STDOUT,"(2).Get Bucket\'s ACL,get_bucket_acl(\$bucket,\$options = NULL):\n");
	fwrite(STDOUT,"(3).Set Bucket\'ACL(Only three types:private,public-read,public-read-write),set_bucket_acl(\$bucket, \$acl,\$options = NULL):\n");
	fwrite(STDOUT,"(4).Create Bucket,create_bucket(\$bucket, \$acl,\$options = NULL):\n");
	fwrite(STDOUT,"(5).Delete Bucket,delete_bucket(\$bucket,\$options = NULL):\n");
	fwrite(STDOUT,"(6).Get Bucket\'s object List,list_object(\$bucket,\$options = NULL):\n");
	fwrite(STDOUT,"(7).Get Bucket\'s object Content,get_object(\$bucket,\$object,\$options = NULL):\n");
	fwrite(STDOUT,"(8).Create Folder(Vitual Folder),create_object_dir(\$bucket,\$object,\$options = NULL):\n");
	fwrite(STDOUT,"(9).Upload File By Http Body,upload_file_by_content(\$bucket,\$object,\$options = NULL):\n");
	fwrite(STDOUT,"(10).Check Object Exist,is_object_exist(\$bucket, \$object,\$options = NULL);\n");
	fwrite(STDOUT,"(11).Get Object Url,get_object_url(\$bucket, \$object,\$options = NULL):\n");
	fwrite(STDOUT,"(12).Get Object Meta,get_object_meta(\$bucket, \$object,\$options = NULL):\n");
	fwrite(STDOUT,"(13).Delete Object,delete_object(\$bucket,\$object,\$options = NULL):\n");
	fwrite(STDOUT,"(14).Create Object Group,create_object_group(\$object_group,\$bucket,\$object_group_array,\$options = NULL):\n");
	fwrite(STDOUT,"(15).Get Object Group,get_object_group(\$object_group,\$bucket,\$options = NULL):\n");
	fwrite(STDOUT,"(16).Get Object Group Index,get_object_group_index(\$object_group,\$bucket,\$options = NULL):\n");
	fwrite(STDOUT,"(17).Get Object Group Meta,get_object_group_meta(\$bucket,\$object_group,\$options = NULL):\n");
	fwrite(STDOUT,"(18).Delete Object Group,delete_object_group(\$bucket,\$object_group,\$options = NULL):\n");
	fwrite(STDOUT,"(19).Continue Next Cycle:\n\n");

	//函数编号
	$fNo = intval(trim(fgets(STDIN)));

	//判断Function No
	if($fNo > 19 || $fNo < 0){
		fwrite(STDOUT, "Invalid Function Number\n,Please Try again\n");
	}else{
		$oss_php_sdk_service = new ALIOSS();
		invoke_function($oss_php_sdk_service,$fNo);
	}
}

//调用函数
function invoke_function($oss_php_sdk_service,$fNo){
	switch ($fNo){
		case 0:
			fwrite(STDOUT, "Exit Function");
			exit();
			break;

		case 1:
			fwrite(STDOUT, "Invoke list_bucket(\$options = NULL)\n");
			fwrite(STDOUT, "Get Bucket List Of Your Account.\n");
			$list_bucket_result = $oss_php_sdk_service->list_bucket();
			fwrite(STDOUT, print_r($list_bucket_result)."\n");
			//print_R($list_bucket_result);
			break;

		case 2:
			fwrite(STDOUT, "Invoke get_bucket_acl(\$bucket,\$options = NULL)");
			break;

		case 3:
			fwrite(STDOUT, "Invoke get_bucket_acl(\$bucket,\$options = NULL)");
			break;

		case 4:
			fwrite(STDOUT, "Invoke create_bucket(\$bucket, \$acl,\$options = NULL)");
			break;

		case 5:
			fwrite(STDOUT, "Invoke delete_bucket(\$bucket,\$options = NULL)");
			break;

		case 6:
			fwrite(STDOUT, "Invoke list_object(\$bucket,\$options = NULL)");
			break;

		case 7:
			fwrite(STDOUT, "Invoke get_object(\$bucket,\$object,\$options = NULL)");
			break;

		case 8:
			fwrite(STDOUT, "Invoke create_object_dir(\$bucket,\$object,\$options = NULL)");
			break;

		case 9:
			fwrite(STDOUT, "Invoke upload_file_by_content(\$bucket,\$object,\$options = NULL)");
			break;

		case 10:
			fwrite(STDOUT, "Invoke is_object_exist(\$bucket, \$object,\$options = NULL)");
			break;

		case 11:
			fwrite(STDOUT, "Invoke get_object_url(\$bucket, \$object,\$options = NULL)");
			break;

		case 12:
			fwrite(STDOUT, "Invoke get_object_meta(\$bucket, \$object,\$options = NULL)");
			break;

		case 13:
			fwrite(STDOUT, "Invoke delete_object(\$bucket,\$object,\$options = NULL)");
			break;

		case 14:
			fwrite(STDOUT, "Invoke create_object_group(\$object_group,\$bucket,\$object_group_array,\$options = NULL)");
			break;

		case 15:
			fwrite(STDOUT, "Invoke get_object_group(\$object_group,\$bucket,\$options = NULL)");
			break;

		case 16:
			fwrite(STDOUT, "Invoke get_object_group_index(\$object_group,\$bucket,\$options = NULL)");
			break;

		case 17:
			fwrite(STDOUT, "Invoke get_object_group_meta(\$bucket,\$object_group,\$options = NULL)");
			break;

		case 18:
			fwrite(STDOUT, "Invoke delete_object_group(\$bucket,\$object_group,\$options = NULL)");
			break;

		case 19:
			continue;
			break;
				
		default:
			continue;
			break;
	}
}



