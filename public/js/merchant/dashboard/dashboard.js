var app = angular.module('app', []);
app.controller('managedashboard', function ($scope, $http, $sce,$timeout, $filter) {
    $scope.filter = {};
    $scope.analytics = {};
    $scope.filter.startDate = $filter('date')(new Date(), 'yyyy-MM-dd');;
    $scope.filter.endDate = $filter('date')(new Date(), 'yyyy-MM-dd');
    $scope.filter.report = 'day';
    $scope.applyFilter = function(){
        $scope.totalOrder = 0;
        $scope.totalConfirmedOrder = 0;  
        $scope.ajaxLoadingData = true;
        $http({
            method: 'POST',
            url: serverMerchantApp + 'dashboard/dashboard',
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
            url: serverMerchantApp + 'dashboard/getTotalDashboardDetail',
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
        $scope.totalOrder = new Array();
        $scope.totalConfirmedOrder = new Array();        
        $scope.totalCancelledOrder = new Array();        
        $scope.dt = new Date(start);
        if($scope.filter.report=='monthly'){
            while ($scope.dt <= end) {
                var formateDate = formatDate($scope.dt);
                $scope.arr.push(formateDate);
                $scope.dt.setMonth($scope.dt.getMonth() + 1);
                if($scope.analytics.customerData.data.allOrderByDate[formateDate] == undefined) {
                    $scope.analytics.customerData.data.allOrderByDate[formateDate] = {}
                   $scope.analytics.customerData.data.allOrderByDate[formateDate].count = 0; 
                }
                $scope.totalOrder.push($scope.analytics.customerData.data.allOrderByDate[formateDate].count);
                if($scope.analytics.customerData.data.completedOrderByDate[formateDate] == undefined) {
                    $scope.analytics.customerData.data.completedOrderByDate[formateDate] = {};
                    $scope.analytics.customerData.data.completedOrderByDate[formateDate].count = 0;
                }
                $scope.totalConfirmedOrder.push($scope.analytics.customerData.data.completedOrderByDate[formateDate].count);
                if($scope.analytics.customerData.data.cancelledOrderByDate[formateDate] == undefined) {
                    $scope.analytics.customerData.data.cancelledOrderByDate[formateDate] = {};
                    $scope.analytics.customerData.data.cancelledOrderByDate[formateDate].count = 0;
                }
                $scope.totalCancelledOrder.push($scope.analytics.customerData.data.cancelledOrderByDate[formateDate].count);                                
                
            }
        }else {
            while ($scope.dt <= end) {
                var formateDate = formatDate($scope.dt);
                $scope.arr.push(formateDate);
                
                
                if($scope.analytics.customerData.data.allOrderByDate[formateDate] == undefined) {
                    $scope.analytics.customerData.data.allOrderByDate[formateDate] = {}
                   $scope.analytics.customerData.data.allOrderByDate[formateDate].count = 0; 
                }
                $scope.totalOrder.push($scope.analytics.customerData.data.allOrderByDate[formateDate].count);
                if($scope.analytics.customerData.data.completedOrderByDate[formateDate] == undefined) {
                    $scope.analytics.customerData.data.completedOrderByDate[formateDate] = {};
                    $scope.analytics.customerData.data.completedOrderByDate[formateDate].count = 0;
                }
                $scope.totalConfirmedOrder.push($scope.analytics.customerData.data.completedOrderByDate[formateDate].count);
                
                if($scope.analytics.customerData.data.cancelledOrderByDate[formateDate] == undefined) {
                    $scope.analytics.customerData.data.cancelledOrderByDate[formateDate] = {};
                    $scope.analytics.customerData.data.cancelledOrderByDate[formateDate].count = 0;
                }
                $scope.totalCancelledOrder.push($scope.analytics.customerData.data.cancelledOrderByDate[formateDate].count);                                
                
                $scope.dt.setDate($scope.dt.getDate() + 1);
            }
        }
        $scope.highcharts($scope.arr, $scope.totalConfirmedOrder, $scope.totalOrder, $scope.totalCancelledOrder);
    };    
        
    $scope.getWeekArray = function(start, end) {

        start = new Date(start);
        end = new Date(end);
        $scope.arr = new Array();
        $scope.totalOrder = new Array();
        $scope.totalConfirmedOrder = new Array();
        $scope.totalCancelledOrder = new Array();        
        $scope.dt = new Date(start);
        while ($scope.dt <= end) {
            var startDate = formatDate($scope.dt)
            var countetStartDt = angular.copy($scope.dt);
            $scope.dt.setDate($scope.dt.getDate() + 7);
            var counterEndDt = $scope.dt;
            var totalNumberOfOrder = 0 ;
            var totalNumberOfConfirmedOrder = 0;
            var totalNumberOfCancelledOrder = 0;            
            while(countetStartDt<=counterEndDt){
                var date = formatDate(countetStartDt);
                if($scope.analytics.customerData.data.allOrderByDate[date] != undefined) {
                    totalNumberOfOrder = totalNumberOfOrder+$scope.analytics.customerData.data.allOrderByDate[date].count;   
                }
                if($scope.analytics.customerData.data.completedOrderByDate[date] != undefined) {
                    totalNumberOfConfirmedOrder = totalNumberOfConfirmedOrder+$scope.analytics.customerData.data.completedOrderByDate[date].count;
                } 
                if($scope.analytics.customerData.data.cancelledOrderByDate[date] != undefined) {
                    totalNumberOfCancelledOrder = totalNumberOfCancelledOrder+$scope.analytics.customerData.data.cancelledOrderByDate[date].count;
                }                
                countetStartDt.setDate(countetStartDt.getDate()+1);
            }
            var endDate = formatDate($scope.dt)
            $scope.arr.push(startDate+' To '+endDate);
            $scope.analytics.customerData.data.allOrderByDate[startDate+' To '+endDate] = {};
            $scope.analytics.customerData.data.completedOrderByDate[startDate+' To '+endDate] = {};
            $scope.analytics.customerData.data.cancelledOrderByDate[startDate+' To '+endDate] = {};            
            $scope.analytics.customerData.data.allOrderByDate[startDate+' To '+endDate].count= totalNumberOfOrder;
            $scope.analytics.customerData.data.completedOrderByDate[startDate+' To '+endDate].count= totalNumberOfConfirmedOrder;
            $scope.analytics.customerData.data.cancelledOrderByDate[startDate+' To '+endDate].count = totalNumberOfCancelledOrder;            
            $scope.totalOrder.push(totalNumberOfOrder);
            $scope.totalConfirmedOrder.push(totalNumberOfConfirmedOrder);            
            $scope.totalCancelledOrder.push(totalNumberOfCancelledOrder);            
            $scope.dt.setDate($scope.dt.getDate() + 1);
        }
        $scope.highcharts($scope.arr, $scope.totalConfirmedOrder, $scope.totalOrder, $scope.totalCancelledOrder);
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
    
    $scope.highcharts = function(category, confirmedOrder, totalOrder, cancelledOrder){
    $('#container14').highcharts({
        title: {
            text: 'Order Status',
            x: 0 //center
        },
        subtitle: {
            text: '',
            x: 0
        },
        xAxis: {
            categories: category
        },
        yAxis: {
            title: {
                text: 'No Of Order'
            },
            plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
                name: 'Total Order Confirmed',
                data: confirmedOrder
            }, {
                name: 'Total Order ',
                data: totalOrder
            }, {
                name: 'Total cancelled Order',
                data: cancelledOrder
            }]
    });        
    };    
});
