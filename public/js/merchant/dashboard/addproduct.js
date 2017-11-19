var app = angular.module('product', []);
app.controller('productController', function ($scope, $http, $sce,$timeout,productList) {
    $scope.errorShow = false;
    $scope.showAttr = false;
    $scope.productData = {};
    $scope.attrbuteData = {};
    $scope.index = 0;
    $scope.indexVal = [];
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
    
    function showAttrDiv() {
        $scope.showAttr = true;
        var a = ($scope.indexVal).length + 1;
        $scope.index++;
        ($scope.indexVal).push(a);
    }
    
    function getCategory() {
        var filter = {};
        filter.categoryHavingNoChild = 1;        
        $http({
            method: 'POST',
            url: serverMerchantApp + 'product/getCategoryList',
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
            url: serverMerchantApp + 'product/taxList',
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
    if($('#attribute_price').val() == ''){
        msg = 'Attribute price should not blank';
        ret = false;
    }
    if($('#store_id').val() == ''){
        msg = 'Please select store';
        ret = false;
    }
    if($('#attribute_stock').val() == ''){
        msg = 'Attribute stock should not blank';
        ret = false;
    }
    
    
   if(!ret){
       alert(msg);
   }
   return ret;
}
