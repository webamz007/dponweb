<template>
    <Layout>
        <Head title="Game" />
        <div class="w-full bg-rk-yellow-light border border-blue-950 shadow p-5 rounded-lg text-gray-500 container mt-5">
            <div class="w-full h-14 bg-rk-blue-dark text-white text-xl text-center flex justify-center items-center container rounded-lg">
                <h2 class="bg-text" v-if="bid_type !== 'market' && bid_type !== 'delhi' && bid_type !== 'starline'"> {{ formatHeading(bid_type) }}</h2>
                <h2 class="bg-text" v-else>{{ market_data.name }}</h2>
            </div>
            <div v-if="bid_type !== 'market' && bid_type !== 'delhi' && bid_type !== 'starline'" class="mb-6 mt-5">
                <label for="types" class="block mb-2 text-base font-medium text-black font-bold">Market</label>
                <div class="flex justify-between items-center">
                    <select @change="clearBid()" v-model="market_id" name="types" id="types" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mr-2">
                        <option value="">--  Select Market -- </option>
                        <option v-for="market in markets" :key="market.id" :value="market.id">{{ market.name }}</option>
                    </select>
                </div>
                <span v-if="errors.market" class="text-sm text-red-700">{{ errors.market }}</span>
            </div>
            <div v-else class="mb-6 mt-5">
                <label for="types" class="block mb-2 text-base font-medium text-black font-bold">Type</label>
                <div v-if="bid_type === 'market'" class="flex justify-between items-center">
                    <select @change="clearBid()" v-model="game_type" name="types" id="types" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mr-2">
                        <option value="default">Default</option>
                        <option value="single_pana">Single Patti</option>
                        <option value="double_pana">Double Patti</option>
                        <option value="tripple_pana">Tripple Patti</option>
                        <option value="half_sangum">Half Sangum</option>
                        <option value="full_sangum">Full Sangum</option>
                        <option value="sp_motors">SP Motors</option>
                        <option value="dp_motors">DP Motors</option>
                    </select>
                </div>
                <div v-else-if="bid_type === 'starline'" class="flex justify-between items-center">
                    <select @change="clearBid()" v-model="game_type" name="types" id="types" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mr-2">
                        <option value="single_digit">Single Digit</option>
                        <option value="single_pana">Single Patti</option>
                        <option value="double_pana">Double Patti</option>
                        <option value="tripple_pana">Tripple Patti</option>
                    </select>
                </div>
                <div v-else class="flex justify-between items-center">
                    <select @change="clearBid" v-model="game_type" name="types" id="types" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mr-2">
                        <option value="ander">Ander</option>
                        <option value="baher">Baher</option>
                        <option value="jodi">Jodi</option>
                    </select>
                </div>
            </div>
            <div v-if="game_type !== 'half_sangum' && game_type !== 'full_sangum' && bid_type !== 'delhi' && bid_type !== 'starline'" class="mb-6 mt-5">
                <label for="types" class="block mb-2 text-base font-medium text-black font-bold">Session</label>
                <div class="flex justify-between items-center">
                    <select @change="clearBid" v-model="session" name="types" id="types" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mr-2">
                        <option value="open">Open</option>
                        <option value="close">Close</option>
                    </select>
                </div>
            </div>
            <div class="mb-6">
                <label for="amount" class="block mb-2 text-base font-medium text-black font-bold">Amount</label>
                <input v-model="amount" type="text" id="amount" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 placeholder-white" placeholder="Enter Amount">
                <span v-if="errors.amount" class="text-sm text-red-700">{{ errors.amount }}</span>
            </div>
            <div class="mb-6">
                <label for="number" class="block mb-2 text-base font-medium text-black">Number</label>
                <div class="flex justify-between items-center">
                    <div v-if="game_type === 'half_sangum' || game_type === 'full_sangum'" class="flex w-full">
                        <input type="text" v-model="open" id="number" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-9/12 md:w-6/12 p-2.5 mr-2 placeholder-white" placeholder="Open">
                        <input type="text" v-model="close" id="number" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-9/12 md:w-6/12 p-2.5 mr-2 placeholder-white" placeholder="Close">
                    </div>
                    <input v-else type="text" v-model="number" id="number" class="bg-rk-blue-dark text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-9/12 md:w-10/12 p-2.5 mr-2 placeholder-white" placeholder="Type your number">
                    <button @click="generateCombinations()" type="button" class="text-white bg-rk-red hover:bg-rk-blue-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-3/12 md:w-2/12 px-5 py-2.5 text-center">Add</button>
                </div>
                <span v-if="errors.number" class="text-sm text-red-700 block">{{ errors.number }}</span>
                <div class="flex justify-center">
                    <button @click="insertBid()" type="button" class="text-white bg-rk-red hover:bg-rk-blue-dark focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full md:w-2/12 px-5 py-2.5 mt-3 text-center">Submit</button>
                </div>
            </div>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-white">
                    <thead class="text-base text-black uppercase bg-rk-gradient-yellow font-bold">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Number
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Amount
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="bg-rk-gradient-yellow border-b border-yellow-400 text-white"
                        v-for="(combo, index) in combinations.slice()"
                        :key="index"
                    >
                        <td class="px-6 py-4 text-black font-bold">
                            {{ combo.number }}
                        </td>
                        <td class="px-6 py-4 text-black font-bold flex justify-between">
                            {{ combo.amount }}
                            <span @click="removeCombo(index, combo.amount)">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-2 cursor-pointer border border-red-400" fill="none" viewBox="0 0 24 24" stroke="red" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </span>
                        </td>
                    </tr>
                    </tbody>
                    <tfoot>
                    <tr class="text-xs text-black uppercase bg-rk-gradient-yellow">
                        <th scope="row" class="px-6 py-3 text-base">Total</th>
                        <td class="px-6 py-3 text-base font-bold">{{ total_amount }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import Layout from "@/Pages/Shared/Layout.vue";
import {Head, usePage} from "@inertiajs/vue3";
import {computed, defineProps, reactive, ref} from "vue";
import axios from "axios";
import moment from "moment";
import 'moment-timezone';


const props = defineProps({
    markets: Object,
    bid_type: String,
    market_data: Object,
})

let errors = reactive({
    amount: '',
    number: '',
    market: '',
});

const page = usePage();
 // let total_points = page.props.auth.user.points;
let points = page.props.auth.user.points;


let game_type = ref('default');
let amount = ref('');
let number = ref('');
let open = ref('');
let close = ref('');
let combinations = ref([]);
let series = ref([]);
let market_id = ref('');
let session = ref('open');
let date = moment().format('YYYY-MM-DD');
let played_on = moment().format('YYYY-MM-DD hh:mm a');
let hold_combination = [];



if (props.bid_type !== 'market' && props.bid_type !== 'delhi' && props.bid_type !== 'starline') {
    game_type.value = props.bid_type;
}
if (props.bid_type === 'delhi') {
    game_type.value = 'ander';
}
if (props.bid_type === 'starline') {
    game_type.value = 'single_digit';
}
if (props.market_data) {
    market_id.value = props.market_data.id;
}

let single_pana = [
    "137", "128", "146", "236", "245", "290", "380", "470", "489", "560", "678", "579",
    "129", "138", "147", "156", "237", "246", "345", "390", "480", "570", "589", "679",
    "120", "139", "148", "157", "238", "247", "256", "346", "490", "580", "670", "689",
    "130", "149", "158", "167", "239", "248", "257", "347", "356", "590", "680", "789",
    "140", "159", "168", "230", "249", "258", "267", "348", "357", "456", "690", "780",
    "123", "150", "169", "178", "240", "259", "268", "349", "358", "367", "457", "790",
    "124", "160", "179", "250", "269", "278", "340", "359", "368", "458", "467", "890",
    "125", "134", "170", "189", "260", "279", "350", "369", "378", "459", "468", "567",
    "126", "135", "180", "234", "270", "289", "360", "379", "450", "469", "478", "568",
    "127", "136", "145", "190", "235", "280", "370", "389", "460", "479", "569", "578"
];
let double_pana = [
    "119", "155", "227", "335", "344", "399", "588", "669", "100",
    "110", "228", "255", "336", "499", "660", "688", "778", "200",
    "166", "229", "337", "355", "445", "599", "779", "788", "300",
    "112", "220", "266", "338", "446", "455", "699", "770", "400",
    "113", "122", "177", "339", "366", "447", "799", "889", "500",
    "114", "277", "330", "448", "466", "556", "880", "899", "600",
    "115", "133", "188", "223", "377", "449", "557", "566", "700",
    "116", "224", "233", "288", "440", "477", "558", "990", "800",
    "117", "144", "199", "225", "388", "559", "577", "667", "900",
    "118", "226", "244", "299", "334", "488", "668", "677", "550"
];

let tripple_pana = [
    "777", "444", "111", "888", "555", "222", "999", "666", "333", "000"
];


const generateCombinations = async () => {
    // Check if market is closed
    if (! await isBettingOpen(market_id.value)) {
        iziToast.error({
            title: 'Error',
            message: 'Betting is closed to this market.',
            position: 'topRight'
        });
        return;
    }

    if (!validateInput()) {
        return;
    }

    const playingNumber = number.value;
    const gameType = game_type.value;

if (props.bid_type === 'starline') {
        switch (gameType) {
            case 'single_digit':
                pushData('single_digit', playingNumber);
                break;
            case 'single_pana':
                if (single_pana.includes(playingNumber)) {
                    pushData('single_pana', playingNumber);
                } else {
                    errors.number = 'Invalid Number';
                    return false;
                }
                break;
            case 'double_pana':
                if (double_pana.includes(playingNumber)) {
                    pushData('double_pana', playingNumber);
                } else {
                    errors.number = 'Invalid Number';
                    return false;
                }
                break;
            case 'tripple_pana':
                if (tripple_pana.includes(playingNumber)) {
                    pushData('tripple_pana', playingNumber);
                } else {
                    errors.number = 'Invalid Number';
                    return false;
                }
                break;
            default:
                errors.number = 'Invalid Number';
                return false;
        }
    }
else if (gameType === 'default') {
    switch (playingNumber.length) {
        case 1:
            pushData('single_digit', playingNumber);
            break;
        case 2:
            pushData('double_digit', playingNumber);
            break;
        case 3:
            if (single_pana.includes(playingNumber)) {
                pushData('single_pana', playingNumber);
            } else if (double_pana.includes(playingNumber)) {
                pushData('double_pana', playingNumber);
            } else if (tripple_pana.includes(playingNumber)) {
                pushData('tripple_pana', playingNumber);
            }
            break;
    }
} else if (gameType === 'single_pana') {
    getGameTypeCombinations(single_pana, playingNumber);
} else if (gameType === 'double_pana') {
    getGameTypeCombinations(double_pana, playingNumber);
} else if (gameType === 'tripple_pana') {
    getGameTypeCombinations(tripple_pana, playingNumber);
} else if (gameType === 'sp_motors') {
    getSpDpMotorsCombinations(single_pana, playingNumber);
} else if (gameType === 'dp_motors') {
    getSpDpMotorsCombinations(double_pana, playingNumber);
} else if (['half_sangum', 'full_sangum', 'ander', 'baher', 'jodi'].includes(gameType)) {
    pushData(gameType, playingNumber);
}

    // Check if combinations are within budget
    let combination_total = getCombinationTotal(hold_combination);
    if (combination_total > points) {
        iziToast.error({
            title: 'Error',
            message: 'No Enough Money',
            position: 'topRight'
        });
        hold_combination = [];
        return;
    }
    points -= combination_total;
    series.value.push(...hold_combination);
    combinations.value = series.value;
    hold_combination = [];
    number.value = '';
    open.value = '';
    close.value = '';
    Array.from(document.querySelectorAll('.wallet-points')).forEach(element => element.textContent = points);
}

const getSpDpMotorsCombinations = (gameTypeArray, inputDigits) => {
    gameTypeArray.forEach(element => {
        if ([...element].every(digit => inputDigits.includes(digit))) {
            pushData(game_type.value, element);
        }
    });
}

const getGameTypeCombinations = (gameTypeArray, inputDigit) => {
    inputDigit = parseInt(inputDigit);
    for (const element of gameTypeArray) {
        const lastDigitSum = element.split('').reduce((sum, digit) => sum + parseInt(digit), 0) % 10;

        if (lastDigitSum === inputDigit) {
            pushData(game_type.value, element);
        }
    }
}

const pushData = (gameType, bidNumber) => {
    // Update session value if needed
    if (['half_sangum', 'full_sangum'].includes(gameType)) {
        session.value = 'open';
        bidNumber = `${open.value}-${close.value}`;
    }

    // Update game type if needed
    switch (gameType) {
        case 'sp_motors':
            gameType = 'single_pana';
            break;
        case 'dp_motors':
            gameType = 'double_pana';
            break;
    }

    // Update session value if needed
    if (props.bid_type === 'delhi') {
        session.value = 'close';
    }

    // Push combination to the hold_combination array
    hold_combination.push({
        amount: amount.value,
        number: bidNumber,
        date,
        played_on,
        market_id,
        session: session.value,
        type: gameType,
        user_id: usePage().props.auth.user.id,
    });
};

const getCombinationTotal = (combinations) => {
    let total = 0;
    for (const combination of combinations) {
        total += parseInt(combination.amount);
    }

    return total;
};

const removeCombo = (index, amount) => {
    combinations.value.splice(index, 1);
    points += parseInt(amount);
    Array.from(document.querySelectorAll('.wallet-points')).forEach(element => element.textContent = points);
};


const total_amount = computed(()=>{
    let total = 0;
    for (const combo of combinations.value) {
        total += parseInt(combo.amount);
    }
    return total;
});

const validateInput = () => {
    if (market_id.value === "") {
        errors.market = 'Please Select Market';
        return false;
    } else {
        errors.market = '';
    }
    if (amount.value.trim() === "") {
        errors.amount = 'Please Enter Amount';
        return false;
    } else {
        errors.amount = '';
    }
    if (amount.value.trim() < 5) {
        errors.amount = 'Please Enter Minimum Amount 5';
        return false;
    } else {
        errors.amount = '';
    }
    if (!(game_type.value === 'half_sangum' || game_type.value === 'full_sangum')) {
        if (number.value.trim() === "") {
            errors.number = 'Please Enter Number';
            return false;
        } else {
            errors.number = '';
        }
    }
    if (game_type.value === 'default') {
        if (number.value.length > 3) {
            errors.number = 'Invalid Number';
            return false;
        }
        if (number.value.length === 2) {
            if (session.value === 'close') {
                errors.number = 'Select Session Open';
                return false;
            }
        }
    }
    if (game_type.value === 'single_pana' || game_type.value === 'double_pana' || game_type.value === 'tripple_pana') {
        if (props.bid_type === 'starline') {
            if (number.value.length !== 3) {
                errors.number = 'Invalid Number';
                return false;
            }
        } else {
            if (number.value.length > 1) {
                errors.number = 'Only Single Digit Allowed';
                return false;
            }
        }
    }
    if (game_type.value === 'single_digit') {
        if (props.bid_type === 'starline') {
            if (number.value.length !== 1) {
                errors.number = 'Invalid Number';
                return false;
            }
        }
    }
    if (game_type.value === 'sp_motors' || game_type.value === 'dp_motors') {
        if (number.value.length <= 3) {
            errors.number = 'Please enter minimum 4 digits';
            return false;
        }
    }
    if (game_type.value === 'half_sangum') {
        var threeDigitInput;
        if (open.value.length === 3 && close.value.length === 1) {
            threeDigitInput = open.value;
        } else if (open.value.length === 1 && close.value.length === 3) {
            threeDigitInput = close.value;
        } else {
            errors.number = 'Invalid Number';
            return false;
        }

        if (!checkAscendingOrder(threeDigitInput)) {
            errors.number = 'Invalid Number';
            return false;
        } else {
            errors.number = '';
        }

    }
    if (game_type.value === 'full_sangum') {
        if (open.value.length !== 3 || close.value.length !== 3) {
            errors.number = 'Invalid Number';
            return false;
        }

        if (!checkAscendingOrder(open.value) || !checkAscendingOrder(close.value)) {
            errors.number = 'Invalid Number';
            return false;
        } else {
            errors.number = '';
        }

    }
    if (props.bid_type === 'delhi') {
        if (game_type.value === 'ander' || game_type.value === 'baher') {
            if (number.value.length !== 1) {
                errors.number = 'Invalid Number';
                return false;
            }
        }
        if (game_type.value === 'jodi') {
            if (number.value.length !== 2) {
                errors.number = 'Invalid Number';
                return false;
            }
        }
        errors.number = '';
    }
    return true;
}

function checkAscendingOrder(input) {
    return (input.includes('0') && input[2] === '0')
        ? String(input).slice(0, 2) === String(input).slice(0, 2).split('').sort().join('')
        : (!input.includes('0') && input === input.split('').sort().join(''));
}

function formatHeading(text) {
    var words = text.split('_');
    for (var i = 0; i < words.length; i++) {
        words[i] = words[i][0].toUpperCase() + words[i].slice(1).toLowerCase();
    }
    return words.join(' ');
}

const clearBid = async () => {
    points = await getUserPoints(points);
    combinations.value = [];
    series.value = [];
    number.value = '';
    amount.value = '';
    Array.from(document.querySelectorAll('.wallet-points')).forEach(element => element.textContent = points);
}

const getUserPoints = async (calPoints) => {
    try {
        const response = await axios.post(route('user-points'));
        return response.data.points;
    } catch (error) {
        return calPoints;
    }
}

const isBettingOpen = async (marketID) => {
    try {
        const response = await axios.post(route('check-market'), { market_id: marketID });
        let market = response.data.market;
        let market_type = response.data.market.market_type;
        let result = response.data.market.result;
        moment.tz.setDefault('Asia/Kolkata');
        const currentTime = moment();
        const openTimeMoment = moment(market.oet, 'h:mm A');
        const closeTimeMoment = moment(market.cet, 'h:mm A');

        if (market_type === 'delhi' || market_type === 'starline') {
            return currentTime.isBefore(openTimeMoment);
        }
        else {
            if (currentTime.isBefore(openTimeMoment)) {
                return true;
            } else if (currentTime.isBetween(openTimeMoment, closeTimeMoment) && result.length == "") {
                return false;
            } else if (currentTime.isBetween(openTimeMoment, closeTimeMoment) && result.length == "5") {
                return true;
            } else if (currentTime.isBetween(openTimeMoment, closeTimeMoment) && result.length == "10") {
                return false;
            } else if (currentTime.isAfter(closeTimeMoment)) {
                return false;
            }
        }

        return false;
    } catch (error) {return false;}
};


const insertBid = () => {
    if (combinations.value.length === 0) {
        iziToast.error({
            title: 'Error',
            message: 'Please Insert Amount Or Number',
            position: 'topRight'
        });
        return;
    }

    let confirmed = confirm("Are you sure you want to submit the bids?");
    if(confirmed) {
        let bid_data = {bids: combinations.value}
        axios.post(route('insert.bid.game'), bid_data)
        .then(response => {
            if (response.data.success) {
                iziToast.success({
                    title: 'Success',
                    message: response.data.msg,
                    position: 'topRight'
                });
                clearBid();
            } else {
                iziToast.error({
                    title: 'Error',
                    message: response.data.msg,
                    position: 'topRight'
                });
            }
        })
        .catch(error => {
            iziToast.error({
                title: 'Error',
                message: error,
                position: 'topRight'
            });
        });
    }

}

</script>

<style scoped>

</style>
