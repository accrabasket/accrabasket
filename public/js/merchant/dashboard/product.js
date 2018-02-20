app.controller('productController', function ($scope, $http, $sce,$timeout) {
    $scope.filterProduct = {};
    $scope.currentPage = 1;
    $scope.filterProduct.page = 1;
    $scope.ajaxLoadingData = false;
    
    $scope.selected_filter_level = 'Action';
    $scope.setFilterType = function(id){
        $scope.filterProduct.filter_type = id;
        $scope.selected_filter_level = id.replace("_", " ");
    }
    $scope.querySearch = function(){
        if($scope.selected_filter_level == 'Action'){
           $scope.errorStatus = true;
           $scope.errorMsg = 'Please select a action '; 
        }
        
        if($scope.filterText == '' || $scope.filterText == undefined){
           $scope.errorStatus = true;
           $scope.errorMsg = 'Please enter '+$scope.selected_filter_level; 
        }
        if(!$scope.errorStatus){
            $scope.filterProduct.value = $scope.filterText;
            delete $scope.filterProduct.page;
            $scope.getProductList();
        }else{
            $timeout(function () {
                $scope.errorStatus = false;
                $scope.errorMsg = '';
            }, 2000);
        }
        
    }
    
    $scope.refresh = function() {
        $scope.filterProduct = {};
        $scope.filterProduct.page = 1;
        $scope.selected_filter_level = 'Action';
        $scope.filterText = '';
        $scope.getProductList();
    }
    
    
   $scope.getProductList = function() {
       $scope.ajaxLoadingData = true;
//       $scope.filterProduct.pagination = 1;
        $http({
            method: 'POST',
            data : ObjecttoParams($scope.filterProduct),
            url: serverMerchantApp + 'product/getproductlist',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.ajaxLoadingData = false;
            $scope.location = {};
            if(response.status == 'success'){
                $scope.productList = response.data;
                $scope.numberOfRecord = response.totalRecord;
            }else{
                $scope.numberOfRecord = 0;
            }
        });
    }
    $scope.getProductList();
    $scope.selectPage = function(page_number) {
        $scope.filterProduct.page = page_number;
        $scope.getProductList();
    };
});
