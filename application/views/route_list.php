<div class="page page-table" ng-controller="routeController">
    <div class="row ui-section">

        <div class="col-lg-8 clearfix">
            <h3 class="section-header">Route Management</h3>
        </div>

        <div class="col-md-12">
            <section class="panel panel-default table-dynamic">
                
           
                <div class="table-filters">
                    <div class="row">
                        <div class="col-sm-4 col-xs-6">
                            <form>
                                <input type="text"
                                   placeholder="Search..."
                                   class="form-control"
                                   data-ng-model="searchKeywords"
                                   data-ng-keyup="search()">
                            </form>
                        </div>
                        <div class="col-sm-3 col-xs-6 filter-result-info">
                            <span>
                             <!--  Showing {{filteredStores.length}}/{{stores.length}} entries-->
                            </span>              
                        </div>
                    </div>
                </div>

                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                    <tr>
                    <th><div class="th">
                        Route Name
                        <span class="fa fa-angle-up"
                                  data-ng-click=" order('name') "
                                  data-ng-class="{active: row == 'name'}">
                        </span>
                        <span class="fa fa-angle-down"
                                  data-ng-click=" order('-name') "
                                  data-ng-class="{active: row == '-name'}">
                        </span>
                        </div>
                    </th>    
                    <th><div class="th">
                        Source City
                        <span class="fa fa-angle-up"
                                  data-ng-click=" order('source_city') "
                                  data-ng-class="{active: row == 'source_city'}">
                        </span>
                        <span class="fa fa-angle-down"
                                  data-ng-click=" order('-source_city') "
                                  data-ng-class="{active: row == '-source_city'}">
                        </span>
                        </div>
                    </th>
                    <th><div class="th">
                        Destination City
                        <span class="fa fa-angle-up"
                                  data-ng-click=" order('destination_city') "
                                  data-ng-class="{active: row == 'destination_city'}">
                        </span>
                        <span class="fa fa-angle-down"
                                  data-ng-click=" order('-destination_city') "
                                  data-ng-class="{active: row == '-destination_city'}">
                        </span>
                        </div>
                    </th>
                    <th><div class="th">
                        Stoppage City
                        <span class="fa fa-angle-up"
                                  data-ng-click=" order('cities') "
                                  data-ng-class="{active: row == 'cities'}">
                        </span>
                        <span class="fa fa-angle-down"
                                  data-ng-click=" order('-cities') "
                                  data-ng-class="{active: row == '-cities'}">
                        </span>
                        </div>
                    </th>
                    <th><div class="th">
                        Action                                
                        </div>
                    </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr data-ng-repeat="store in currentPageStores | orderBy : '-last_modified_date'">
                        <td>{{store.name}}</td>
                        <td>{{store.source_city}}</td>
                        <td>{{store.destination_city}}</td>
                        <td>{{store.cities}}</td>
                        <td width="25%">
                           <a href="" data-ng-click="editRoute(store.id)"><span class="md-raised btn-w-xs md-accent md-button md-default-theme">Edit</span></a> 
                            <a href="" data-ng-click="deleteRoute(store.id)"><span class="md-raised btn-w-xs md-warn md-button md-default-theme">Delete</span></a>


                        </td>
                        </tr>
                    </tbody>
                </table>

                    <footer class="table-footer">
                        <div class="row">
                            <div class="col-md-6 page-num-info">
                                <span>
                                    Show 
                                    <select data-ng-model="numPerPage"
                                            data-ng-options="num for num in numPerPageOpt"
                                            data-ng-change="onNumPerPageChange()">
                                    </select> 
                                    entries per page
                                </span>
                            </div>
                            <div class="col-md-6 text-right pagination-container">
                                <pagination class="pagination-sm"
                                            ng-model="currentPage"
                                            total-items="filteredStores.length"
                                            max-size="4"
                                            ng-change="select(currentPage)"
                                            items-per-page="numPerPage"
                                            rotate="false"
                                            previous-text="&lsaquo;" next-text="&rsaquo;"
                                            boundary-links="true"></pagination>
                            </div>
                        </div>
                    </footer>
            </section>
                </div>
            </div>
        </div>
    </div>
</div>

