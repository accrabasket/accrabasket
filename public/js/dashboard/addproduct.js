var app = angular.module('product', []);
app.controller('productController', function ($scope, $http, $sce,$timeout,productList) {
    $scope.errorShow = false;
    $scope.showAttr = false;
    $scope.productData = {};
    $scope.attrbuteData = {};
    $scope.index = 0;
    $scope.indexVal = [];
    $scope.indexDis = 0;
    $scope.indexValDis = [];
    $scope.taxList = {};
    if(productList != ''){
        for(var i=1; i <= productList ;i++){
            console.log(productList);
            //showAttrDiv();
        }
    }
    $scope.showAttrDiv = function(){
        $scope.showAttr = true;
         var a = ($scope.indexVal).length +1;
          $scope.index++;
         ($scope.indexVal).push(a);
    }
    
    $scope.showAttrDivDis = function(){
        $scope.showAttrDis = true;
         var a = ($scope.indexValDis).length +1;
          $scope.indexDis++;
         ($scope.indexValDis).push(a);
    }
    
    function showAttrDiv() {
        $scope.showAttr = true;
        var a = ($scope.indexVal).length + 1;
        $scope.index++;
        ($scope.indexVal).push(a);
    }
    function getPromotion() {
        var filter = {};
        filter.categoryHavingNoChild = 1;        
        $http({
            method: 'POST',
            url: serverAdminApp + 'product/getPromotionList',
            data : ObjecttoParams(filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.categoryList = {};
            if(response.status == 'success'){
                $scope.categoryList = response.data;
            }
        });
    }    
    getPromotion();
    
    function getCategory() {
        var filter = {};
        filter.categoryHavingNoChild = 1;        
        $http({
            method: 'POST',
            url: serverAdminApp + 'product/getCategoryList',
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
    
    function getTax() {      
        $http({
            method: 'POST',
            url: serverAdminApp + 'product/taxList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            if(response.status == 'success'){
                $scope.taxList = response.data;
            }
        });
    }    
    getTax();
    
	
});	


function checkform(){
    var ret  = true;
    var msg = '';
    if($('#product_name').val() == ''){
        msg = 'Product name should not blank';
        ret = false;
    }
//    if($('#category_id').val() == ''){
//        msg = 'Product category id should not blank';
//        ret = false;
//    }
    if($('#item_code').val() == ''){
        msg = 'Item code should not blank';
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
        if($('#attribute_commission_type_'+newindex).val() == 'percent' && $('#attribute_commission_value_'+newindex).val() > 70){
            msg = 'Commission percent should be less then 70 %';
            ret = false;
        }
   }
   if(!ret){
       alert(msg);
   }
   return ret;
}
