export interface CalculatorSuccessResponse {
    results: Array<CalculationResult>
}

export interface CalculationResult {
    exchangeRate: number
    amount: number
    currency: string
}

export interface CalculatorErrorResponse {
    message: string
    errors: { [key: string]: string }
}
