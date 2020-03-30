
 <div class="container-fluid" id="order_list">
    <div class="row">
        <div class="col" style="margin-top: 108px;">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0"><i class="fas fa-store"></i> Order Lists
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="min-height: 400px;">
                        <!-- if orders is not empty -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#SN</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Restaurant</th>
                                    <th class="text-center">Priority</th>
                                    <th>Payment Type</th>
                                    <th class="text-center">Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(order, key) in orders" :key="key">
                                    <th>#{{ key + 1}}</th>
                                    <th scope="row">
                                        {{ order.customer.customer_name }}
                                    </th>
                                    <td>${{ order.total_price }}</td>
                                    <td>{{ order.restaurant.restaurant_name }}</td>
                                    <td>{{ order.order_priority === 1 ? 'As fast as possible' : order.order_priority === 2 ? 'In one hour' : 'In two hours' }}</td>
                                    <td>{{ order.payment_type === 1 ? 'Cash' :  order.payment_type === 2 ? 'Paypal' : 'Bikash' }}</td>
                                    <td><span class="badge" :class="[ order.order_status == 1 ? 'badge-warning' : order.order_status === 2 ? 'badge-success' : 'badge-danger']">{{ order.order_status == 1 ? 'Received' : order.order_status === 2 ? 'Complete' : 'New' }}</span></td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a 
                                                    class="dropdown-item" 
                                                    v-if="hasPermission('edit', 'order')" 
                                                    href="#"
                                                    @click="preview(order)">
                                                        <i class="fas fa-edit text-primary"></i> Details
                                                </a>
                                                <a class="dropdown-item" v-if="hasPermission('delete', 'order')" href="#" @click="remove(order.id)"><i class="fas fa-trash text-danger"></i> Remove</a>
                                                <a 
                                                    class="dropdown-item" 
                                                    v-if="hasPermission('edit', 'order')" 
                                                    href="#"
                                                    @click="edit(order)">
                                                        <i class="fas fa-edit text-primary"></i> Edit
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4">
                    <!-- pagination links -->
                    <nav aria-label="..." v-html="links"></nav>
                </div>
            </div>
        </div>
    </div>
    <!-- order Edit Modal -->
    <div class="modal fade" id="orderPreviewModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="orderEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="flex: 1;">Details
                        <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </h3>
                </div>
                <div class="lds-ripple" v-if="isLoading">
                    <div></div>
                    <div></div>
                </div>
                <div class="table-responsive" :class="[isLoading ? 'v-hidden' : '']">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#SN</th>
                                <th>Food</th>
                                <th>Size</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Aditional Price</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(food_price, key) in order.food_prices" :key="key">
                                <th>#{{ key + 1}}</th>
                                <th scope="row">
                                    {{ food_price.food.food_name }}
                                </th>
                                <td>{{ food_price.food_size.food_size_name }}</td>
                                <td class="text-right">${{ food_price.food_price }}</td>
                                <td class="text-right">${{ food_price.aditional_price }}</td>
                                <td class="text-right">${{ sum(food_price.food_price, food_price.aditional_price) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th>Total</th>
                        <th class="text-right">${{ totalPrice }}</th>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>