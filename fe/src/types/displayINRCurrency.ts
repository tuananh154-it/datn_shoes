const displayINRCurrency = (num:number) => {
    const formatter = new Intl.NumberFormat('en-IN',{
        style : "currency",
        currency : 'VND',
        minimumFractionDigits : 3
    })

    return formatter.format(num)

}

export default displayINRCurrency