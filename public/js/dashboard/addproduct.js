var app = angular.module('product', []);
app.controller('productController', function ($scope, $http, $sce,$timeout) {
    $scope.errorShow = false;
    $scope.showAttr = false;
    $scope.productData = {};
    $scope.attrbuteData = {};
    $scope.index = 0;
    $scope.indexVal = [];
    $scope.showAttrDiv = function(){
        $scope.showAttr = true;
         var a = ($scope.indexVal).length +1;
          $scope.index++;
         ($scope.indexVal).push(a);
    }
    function getCategory() {
        var filter = {};
        filter.categoryHavingNoChild = 1;        
        $http({
            method: 'POST',
            url: serverAdminApp + 'dashboard/getCategoryList',
            data : ObjecttoParams(filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.categoryList = {};
            if(response.status == 'success'){
                $scope.categoryList = response.data;
            }
        });
    }    
    getCategory();
    
	
});	


function checkform(){
    var ret  = true;
    var msg = '';
    if($('#product_name').val() == ''){
        msg = 'Product name should not blank';
        ret = false;
    }
    if($('#category_id').val() == ''){
        msg = 'Product category id should not blank';
        ret = false;
    }
    if($('#product_name').val() == ''){
        msg = 'Product name should not blank';
        ret = false;
    }
    
    var index = $('#index').val();
    
   for(var i=0; i<index ; i++){
       var newindex  = i+1;
       if($('#attribute_name_'+newindex).val() == ''){
            msg = 'attribute name should not blank';
            ret = false;
        }
        if($('#attribute_quantity_'+newindex).val() == ''){
            msg = 'quantity should not blank';
            ret = false;
        }
        if($('#attribute_unit_'+newindex).val() == ''){
            msg = 'Unit should not blank';
            ret = false;
        }
   }
   if(!ret){
       alert(msg);
   }
   return ret;
}
