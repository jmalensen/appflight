<template>
    <div class="container pt-4">
        <h1 class="text-center"><i class="fa-solid fa-plane-up"></i> Flight Ticket Booking</h1>

        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchDarkMode" v-model="isDarkMode">
            <label class="form-check-label" for="flexSwitchDarkMode"><span v-html="switchLabel"></span></label>
        </div>

        <!-- Booking ticket with selection of flight -->
        <div class="mb-3 card">
            <div class="card-body">
                <h2>Booking ticket</h2>

                <div class="form-group mb-3">
                    <label for="flight-select"><i class="fa-solid fa-plane-departure"></i> Select flight</label>
                    <select class="form-control" v-model="selectedFlight" id="flight-select" @change="handleChangeFlight">
                        <option value="0" selected>Select flight</option>
                        <option v-for="flight in flights" :key="flight.id" :value="flight.id">
                            {{ flight.source_airport }} to {{ flight.destination_airport }} at {{ flight.departure_time }}
                        </option>
                    </select>

                    <em v-if="errors.booking.selectedFlight" class="text-error">{{ errors.booking.selectedFlight }}</em>
                </div>
                
                <div class="form-group">
                    <div class="mb-3">
                        <label><i class="fa-solid fa-passport"></i> Passport number</label>
                        <input class="form-control" type="text" v-model="passportNumber" placeholder="Passport number" @input="handleChangePassportNumber" />
                        <em v-if="errors.booking.passportNumber" class="text-error">{{ errors.booking.passportNumber }}</em>
                    </div>

                    <div class="mb-2">
                        <label><i class="fa-solid fa-user"></i> Passenger name</label>
                        <input class="form-control" type="text" v-model="passengerName" placeholder="Passenger name" @input="handleChangePassengerName" />
                        <em v-if="errors.booking.passengerName" class="text-error">{{ errors.booking.passengerName }}</em>
                    </div>

                    <button class="btn btn-primary" @click="createTicket">Create ticket</button>
                </div>
            </div>
        </div>

        <hr class="my-4" />

        <!-- Cancel ticket -->
        <div class="mb-3 card">
            <div class="card-body">
                <h2>Cancel ticket</h2>
                <div class="form-group">
                    <div class="mb-2">
                        <label><i class="fa-solid fa-ticket-simple"></i> Ticket number</label>
                        <input class="form-control" type="text" v-model="ticketNumberToCancel" placeholder="Ticket number to cancel" @input="handleChangeTicketNumberToCancel" />
                        <em v-if="errors.cancelTicket" class="text-error">{{ errors.cancelTicket }}</em>
                    </div>

                    <button class="btn btn-primary" @click="cancelTicket">Cancel ticket</button>
                </div>
            </div>
        </div>

        <hr class="my-4" />

        <!-- Change seat -->
        <div class="mb-3 card">
            <div class="card-body">
                <h2>Change seat</h2>
                <div class="form-group">
                    <div class="mb-2">
                        <label><i class="fa-solid fa-ticket-simple"></i> Ticket number</label>
                        <input class="form-control" type="text" v-model="ticketNumberToChangeSeat" placeholder="Ticket number to change seat" @input="handleChangeTicketNumberToChangeSeat" />
                        <em v-if="errors.changeSeat" class="text-error">{{ errors.changeSeat }}</em>
                    </div>

                    <button class="btn btn-primary" @click="changeSeat">Change seat</button>
                </div>
            </div>
        </div>
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
                ticketNumberToCancel: '',
                ticketNumberToChangeSeat: '',
                isDarkMode: false,
                errors: {
                    booking: {},
                    cancelTicket: '',
                    changeSeat: '',
                },
            };
        },
        mounted() {
            this.getFlights();
        },
        computed: {
            switchLabel() {
                return this.isDarkMode ? 'Dark mode <i class="fa-solid fa-moon"></i>' : 'Light mode <i class="fa-solid fa-sun"></i>';
            }
        },
        watch: {
            isDarkMode(newVal) {
                if (newVal) {
                    document.body.classList.add('dark-mode');
                } else {
                    document.body.classList.remove('dark-mode');
                }
            }
        },
        methods: {
            handleChangeFlight(){
                // Check that a flight is selected
                if (!this.selectedFlight) {
                    this.errors.booking.selectedFlight = 'Please select a flight';
                } else{
                    this.errors.booking.selectedFlight = '';
                }
            },
            handleChangePassportNumber(){
                // Check that a passportNumber is entered
                if (this.passportNumber == '') {
                    this.errors.booking.passportNumber = 'Please enter a passport number';
                } else{
                    this.errors.booking.passportNumber = '';
                }
            },
            handleChangePassengerName(){
                // Check that a passengerName is entered
                if (this.passengerName == '') {
                    this.errors.booking.passengerName = 'Please enter a passenger name';
                } else{
                    this.errors.booking.passengerName = '';
                }
            },

            handleChangeTicketNumberToCancel(){
                // Check that a TicketNumber is entered
                if (this.ticketNumberToCancel == '') {
                    this.errors.cancelTicket = 'Please enter a ticket number';
                } else{
                    this.errors.cancelTicket = '';
                }
            },
            handleChangeTicketNumberToChangeSeat(){
                // Check that a TicketNumber is entered
                if (this.ticketNumberToChangeSeat == '') {
                    this.errors.changeSeat = 'Please enter a ticket number';
                } else{
                    this.errors.changeSeat = '';
                }
            },

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

                this.clearErrors();

                // Check that a flight is selected
                if (!this.selectedFlight) {
                    this.errors.booking.selectedFlight = 'Please select a flight';
                    return;
                }

                // Check that a passportNumber is entered
                if(this.passportNumber == ''){
                    this.errors.booking.passportNumber = 'Please enter a passport number';
                    return;
                }
                // Check that a valid passportNumber is entered
                const passportRegex = /^[a-zA-Z0-9]{6,9}$/;
                if (!passportRegex.test(this.passportNumber)) {
                    this.errors.booking.passportNumber = 'Please enter a valid passport number';
                    return;
                }

                // Check that a passengerName is entered
                if(this.passengerName == ''){
                    this.errors.booking.passengerName = 'Please enter a passenger name';
                    return;
                }
                // Check that a valid passengerName is entered
                const passengerRegex = /^[a-zA-Z]*$/;
                if (!passengerRegex.test(this.passengerName)) {
                    this.errors.booking.passengerName = 'Please enter a valid passenger name';
                    return;
                }

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

                    // Clear input fields after successful creation
                    this.selectedFlight = 0;
                    this.passportNumber = '';
                    this.passengerName = '';

                } catch (error) {
                    console.error('Error booking ticket:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error booking ticket: '+ error.response.data.message,
                        timer: 4000,
                        timerProgressBar: true,
                    });
                }
            },

            // Async method to cancel specified ticket number
            async cancelTicket() {
                // Check that a ticketNumber is entered
                if(this.ticketNumberToCancel == ''){
                    this.errors.cancelTicket = 'Please enter a ticket number to cancel';
                    return;
                }

                try {
                    await axios.delete(`/api/tickets/${this.ticketNumberToCancel}`);
                    Swal.fire({
                        icon: 'success',
                        title: 'Ticket cancelled successfully',
                        text: 'Ticket is successfully cancelled!',
                        timer: 4000,
                        timerProgressBar: true,
                    });

                } catch (error) {
                    console.error('Error cancelling ticket:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error cancelling ticket: '+ error.response.data.message,
                        timer: 4000,
                        timerProgressBar: true,
                    });
                }
            },

            // Async method to change seat of specified ticket number
            async changeSeat() {
                // Check that a ticketNumber is entered
                if(this.ticketNumberToChangeSeat == ''){
                    this.errors.changeSeat = 'Please enter a ticket number to change the seat';
                    return;
                }

                try {
                    const response = await axios.put(`/api/tickets/${this.ticketNumberToChangeSeat}/seat`);
                    Swal.fire({
                        icon: 'success',
                        title: 'Seat updated successfully',
                        text: 'Ticket number: '+response.data.ticket.ticket_number +' New seat: '+ response.data.ticket.seat,
                    });

                } catch (error) {
                    console.error('Error updating seat:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error updating seat: '+ error.response.data.message,
                        timer: 4000,
                        timerProgressBar: true,
                    });
                }
            },

            // Reset errors texts
            clearErrors() {
                this.errors = {
                    booking: {},
                    cancelTicket: '',
                    changeSeat: '',
                };
            },
        }
    };
</script>