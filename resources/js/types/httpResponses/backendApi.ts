export interface CalculatorSuccessResponse {
    results: Array<CalculationResult>
}

export interface CalculationResult {
    exchangeRate: Number
    amount: Number
    currency: String
}

export interface CalculatorErrorResponse {
    message: String
    errors: Object<ValidationErrors>
}

export interface ValidationErrors {
    errors: Array
}
