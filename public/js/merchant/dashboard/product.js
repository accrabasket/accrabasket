app.controller('productController', function ($scope, $http, $sce,$timeout) {
    $scope.filterProduct = {};
    $scope.currentPage = 1;
    $scope.filterProduct.page = 1;
    $scope.ajaxLoadingData = false;
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
