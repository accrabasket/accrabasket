var app = angular.module('product', ['ui.bootstrap']);
app.controller('productOutOfStock', function ($scope, $http, $timeout) {
    $scope.filterProduct = {};
    $scope.currentPage = 1;
    $scope.filterProduct.page = 1;
    
   $scope.getProductOutOFStock = function() {
       //$scope.filterProduct.pagination = 1;
        $http({
            method: 'POST',
            data : ObjecttoParams($scope.filterProduct),
            url: serverAdminApp + 'product/stockList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.location = {};
            if(response.status == 'success'){
                $scope.productList = response.data;
                $scope.numberOfRecord = response.totalNumberOfOrder;
            }
        });
    }
    $scope.getProductOutOFStock();
    $scope.selectPage = function(page_number) {
        $scope.filterProduct.page = page_number;
        $scope.currentPage = page_number;
        $scope.getProductOutOFStock();
    };
});
