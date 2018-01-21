var app = angular.module('app', []);
app.controller('managedashboard', function ($scope, $http, $sce,$timeout, $filter) {
    $scope.filter = {};
    $scope.analytics = {};
    $scope.filter.startDate = $filter('date')(new Date(), 'yyyy-MM-dd');;
    $scope.filter.endDate = $filter('date')(new Date(), 'yyyy-MM-dd');
    $scope.applyFilter = function(){
        $scope.totalOrder = 0;
        $scope.totalConfirmedOrder = 0;  
        $scope.ajaxLoadingData = true;
        $http({
            method: 'POST',
            url: serverAdminApp + 'dashboard/dashboard',
            data : ObjecttoParams($scope.filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.analytics = response;
            if($scope.filter.report=='weekly'){
                $scope.getWeekArray($scope.filter.startDate,$scope.filter.endDate);
            }else{
                $scope.getDateArray($scope.filter.startDate,$scope.filter.endDate);
            }
            $scope.ajaxLoadingData = false;
        });        
    }
    $scope.applyFilter();
    
    $scope.getTotalDashboardDetail = function() {
        $http({
            method: 'POST',
            url: serverAdminApp + 'dashboard/getTotalDashboardDetail',
            data : ObjecttoParams($scope.filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.totalActiveCustomer = response.totalActiveCustomer;
            $scope.totalNumberOfMerchant = response.totalNumberOfMerchant;
            $scope.totalNumberOfProduct = response.totalNumberOfProduct;
        });        
    }
    
    $scope.getTotalDashboardDetail();    
    
    $scope.getDateArray = function(start, end) {

        start = new Date(start);
        end = new Date(end);
        $scope.arr = new Array();
        $scope.dt = new Date(start);
        if($scope.filter.report=='monthly'){
            while ($scope.dt <= end) {
                $scope.arr.push(formatDate($scope.dt));
                $scope.dt.setMonth($scope.dt.getMonth() + 1);
            }
        }else {
            while ($scope.dt <= end) {
                $scope.arr.push(formatDate($scope.dt));
                $scope.dt.setDate($scope.dt.getDate() + 1);
            }
        }
    };    
        
    $scope.getWeekArray = function(start, end) {

        start = new Date(start);
        end = new Date(end);
        $scope.arr = new Array();
        $scope.dt = new Date(start);
        while ($scope.dt <= end) {
            var startDate = formatDate($scope.dt)
            var countetStartDt = angular.copy($scope.dt);
            $scope.dt.setDate($scope.dt.getDate() + 7);
            var counterEndDt = $scope.dt;
            var totalNumberOfOrder = 0 ;
            var totalNumberOfConfirmedOrder = 0;
            while(countetStartDt<=counterEndDt){
                var date = formatDate(countetStartDt);
                if($scope.analytics.customerData.data.allOrderByDate[date] != undefined) {
                    totalNumberOfOrder = totalNumberOfOrder+$scope.analytics.customerData.data.allOrderByDate[date].count;   
                }
                if($scope.analytics.customerData.data.completedOrderByDate[date] != undefined) {
                    totalNumberOfConfirmedOrder = totalNumberOfConfirmedOrder+$scope.analytics.customerData.data.completedOrderByDate[date].count;
                } 
                countetStartDt.setDate(countetStartDt.getDate()+1);
            }
            var endDate = formatDate($scope.dt)
            $scope.arr.push(startDate+' To '+endDate);
            $scope.analytics.customerData.data.allOrderByDate[startDate+' To '+endDate] = {};
            $scope.analytics.customerData.data.completedOrderByDate[startDate+' To '+endDate] = {};
            $scope.analytics.customerData.data.allOrderByDate[startDate+' To '+endDate].count= totalNumberOfOrder;
            $scope.analytics.customerData.data.completedOrderByDate[startDate+' To '+endDate].count= totalNumberOfConfirmedOrder;
            $scope.dt.setDate($scope.dt.getDate() + 1);
        }
    };
    
    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;
        if($scope.filter.report == 'monthly'){
            return [year, month].join('-');
        }else{
            return [year, month, day].join('-');
        }
    }    
});
