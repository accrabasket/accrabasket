app.controller('productController', function ($scope, $http, $sce,$timeout) {
    $scope.filterProduct = {};
   $scope.productList = function() {
       $scope.filterProduct.pagination = 1;
        $http({
            method: 'POST',
            data : ObjecttoParams($scope.filterProduct),
            url: serverMerchantApp + 'product/getproductlist',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.location = {};
            if(response.status == 'success'){
                $scope.productList = response.data;
            }
        });
    }
    $scope.productList();
});
