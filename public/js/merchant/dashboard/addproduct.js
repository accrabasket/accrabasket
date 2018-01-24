var app = angular.module('product', []);
app.controller('productController', function ($scope, $http, $sce, $timeout, inventryDetail, store_id) {
    $scope.errorShow = false;
    $scope.showAttr = false;
    $scope.productData = {};
    $scope.attrbuteData = {};
    $scope.index = 0;
    $scope.indexVal = [];
    $scope.taxList = {};
    $scope.store_id = store_id;
    $scope.inventryDetails = inventryDetail;
    $scope.inventryData = {};
    $scope.showAttrDiv = function () {
        $scope.showAttr = true;
        var a = ($scope.indexVal).length + 1;
        $scope.index++;
        ($scope.indexVal).push(a);
    }
    
    $scope.changeStore = function() {
        $scope.inventryData = {};
        if($scope.inventryDetails[$scope.store_id] != undefined) {
            $scope.inventryData = $scope.inventryDetails[$scope.store_id];
        }
    }
    $scope.changeStore();
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
            data: ObjecttoParams(filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.categoryList = {};
            if (response.status == 'success') {
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
            if (response.status == 'success') {
                $scope.taxList = response.data;
            }
        });
    }
    getTax();


});


function checkform() {
    var ret = true;
    var msg = '';
    if ($('#attribute_price').val() == '') {
        msg = 'Attribute price should not blank';
        ret = false;
    }
    if ($('#store_id').val() == '') {
        msg = 'Please select store';
        ret = false;
    }
    if ($('#attribute_stock').val() == '') {
        msg = 'Attribute stock should not blank';
        ret = false;
    }


    if (!ret) {
        alert(msg);
    }
    return ret;
}
