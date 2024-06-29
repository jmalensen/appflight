<template>
    <div class="container pt-4">
        <h1 class="text-center"><i class="fa-solid fa-plane-up"></i> Flight Ticket Booking</h1>

        <!-- Booking ticket with selection of flight -->
        <div class="mb-3 card">
            <div class="card-body">
                <h2>Booking ticket</h2>

                <div class="form-group mb-3">
                    <label for="flight-select"><i class="fa-solid fa-plane-departure"></i> Select flight</label>
                    <select class="form-control" v-model="selectedFlight" id="flight-select">
                        <option value="0" selected>Select flight</option>
                        <option v-for="flight in flights" :key="flight.id" :value="flight.id">
                            {{ flight.source_airport }} to {{ flight.destination_airport }} at {{ flight.departure_time }}
                        </option>
                    </select>
                </div>
                
                <div class="form-group">
                    <div class="mb-3">
                        <label><i class="fa-solid fa-passport"></i> Passport number</label>
                        <input class="form-control" type="text" v-model="passportNumber" placeholder="Passport number" />
                    </div>

                    <div class="mb-2">
                        <label><i class="fa-solid fa-user"></i> Passenger name</label>
                        <input class="form-control" type="text" v-model="passengerName" placeholder="Passenger name" />
                    </div>

                    <button class="btn btn-primary" @click="createTicket">Create ticket</button>
                </div>
            </div>
        </div>

        <hr class="my-4" />
    </div>
</template>

<script>
    import axios from 'axios';
    import Swal from 'sweetalert2';

    export default {
        data() {
            return {
                flights: [],
                selectedFlight: 0,
                passportNumber: '',
                passengerName: '',
            };
        },
        mounted() {
            this.getFlights();
        },
        methods: {

            // Async method to get all flights in DB
            async getFlights() {
                try {
                    const response = await axios.get('/api/flights');
                    this.flights = response.data.flights;

                } catch (error) {
                    console.error('Error fetching flights:', error);
                }
            },

            // Async method to create a ticket for a specified flight
            async createTicket() {

                try {
                    const response = await axios.post('/api/tickets', {
                        flight_id: this.selectedFlight,
                        passport_number: this.passportNumber,
                        name: this.passengerName
                    });
                    Swal.fire({
                        icon: 'success',
                        title: 'Ticket created successfully',
                        text: 'Ticket number: '+response.data.ticket.ticket_number +' Seat: '+ response.data.ticket.seat,
                    });

                } catch (error) {
                    console.error('Error booking ticket:', error);
                }
            },

        }
    };
</script>