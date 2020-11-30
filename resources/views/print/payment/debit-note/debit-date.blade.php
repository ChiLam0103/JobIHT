<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 10pt "Tohoma";
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .page {
        width: 21cm;
        overflow: hidden;
        min-height: 297mm;
        padding: 0.5cm;
        margin-left: auto;
        margin-right: auto;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    @page {
        size: A4;
        margin: 0;
    }

    @media print {
        @page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }

    .font-weight-bold {
        font-weight: bold;
    }

    .title {
        font-weight: bold;
        text-align: center;
    }

    .title p {
        /* font-size: 13px; */
        margin-top: -1em;
    }

    .title #comp_name {
        margin-top: 0.1em;
        font-size: 20px;
    }

    .title h1 {
        margin-top: 0em;
    }

    .text-center {
        text-align: center
    }

    .text-left {
        text-align: left
    }

    .text-right {
        text-align: right
    }

    #recevie p {
        text-align: center;
        font-weight: bold;
        border-bottom: 1px solid;
        margin: 0;
    }

    #recevie td:first-child,
    #info-debit-2 td:first-child {
        font-weight: bold;
    }

    #info-debit-2 td:first-child {
        width: 25%;
    }

    #info-debit td {
        padding-top: .5em;
    }

    #info-debit td:first-child {
        width: 40%;
    }

    #info-debit td:nth-child(2) {
        font-size: 16px;
        font-weight: bold;
    }

    #debit_d,
    #debit_d th,
    #debit_d td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    tr td {
        padding-top: 0.2em;
        font-size: 13px
    }

    .border {
        border: 1px solid;
    }

    .col-10 {
        width: 100%;
        display: flex;
    }

    .col-9 {
        width: 90%;
    }

    .col-8 {
        width: 80%;
    }

    .col-7 {
        width: 70%;
    }

    .col-6 {
        width: 60%;
    }

    .col-5 {
        width: 50%;
    }

    .col-4 {
        width: 40%;
    }

    .col-3 {
        width: 30%;
    }

    .col-25 {
        width: 25%;
    }

    .col-2 {
        width: 20%;
    }

    .col-1 {
        width: 10%;
    }

</style>

<body onload="window.print();">
    <div id="page" class="page">

    </div>

</body>
